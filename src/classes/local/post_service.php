<?php

namespace local_chuspasocial\local;

defined('MOODLE_INTERNAL') || die();

use core_tag_tag;
use local_chuspasocial\local\persistent\post;
use moodle_exception;

/**
 * Servicio para la gestion de publicaciones (posts).
 *
 * @package local_chuspasocial
 */
class post_service {

    /**
     * Actualiza el contenido y formato de un post existente.
     *
     * @param int $postid ID de la publicacion a actualizar.
     * @param string $content Nuevo contenido.
     * @param int $format Formato del contenido.
     * @param array $tags Tags de la publicacion.
     * @throws moodle_exception Si el usuario actual no es el autor.
     */
    public static function update_post(int $postid, string $content, int $format, array $tags = []): void {
        global $USER;

        // Instanciar la publicacion existente.
        $post = new post($postid);

        // Verificar que el usuario actual sea el autor.
        if ((int) $post->get('userid') !== (int) $USER->id) {
            throw new moodle_exception(
                'cannotupdatepost',
                'local_chuspasocial',
                '',
                null,
                'Solo el autor puede editar esta publicacion.'
            );
        }

        // Actualizar los datos.
        $post->set('content', $content);
        $post->set('format', $format);
        $post->set('timemodified', time());
        $post->set('usermodified', $USER->id);

        // Guardar los cambios.
        $post->update();

        // Determinar el contexto de la publicacion.
        if ((int) $post->get('courseid') > 0) {
            $context = \context_course::instance((int) $post->get('courseid'));
        } else {
            $context = \context_system::instance();
        }

        // Actualizar los tags de la publicacion.
        core_tag_tag::set_item_tags(
            'local_chuspasocial',
            'local_chuspasocial_post',
            $postid,
            $context,
            $tags
        );
    }

    /**
     * Elimina una publicacion, sus comentarios, reacciones y archivos.
     *
     * @param int $postid ID de la publicacion que se eliminara.
     * @throws \moodle_exception Si la publicacion no existe o el usuario no esta autorizado.
     */
    public static function delete_post(int $postid): void {
        global $DB, $USER;

        // Buscar la publicacion.
        $post = $DB->get_record(
            'local_chuspasocial_post',
            ['id' => $postid],
            '*',
            MUST_EXIST
        );

        // Determinar el contexto de la publicacion.
        if ((int) $post->courseid > 0) {
            $context = \context_course::instance((int) $post->courseid);
        } else {
            $context = \context_system::instance();
        }

        // Verificar si el usuario es el autor.
        $isauthor = (int) $post->userid === (int) $USER->id;

        // Comprobar el permiso de moderacion solo si esta definido.
        $ismoderator = false;
        if (get_capability_info('local/chuspasocial:moderate')) {
            $ismoderator = has_capability(
                'local/chuspasocial:moderate',
                \context_system::instance()
            );
        }

        // Solo el autor o un moderador pueden eliminar la publicacion.
        if (!$isauthor && !$ismoderator) {
            throw new \moodle_exception(
                'nopermissions',
                'error',
                '',
                'delete this post'
            );
        }

        // Iniciar la transaccion.
        $transaction = $DB->start_delegated_transaction();

        try {
            // Eliminar los comentarios de la publicacion.
            $DB->delete_records(
                'local_chuspasocial_comment',
                ['postid' => $postid]
            );

            // Eliminar las reacciones de la publicacion.
            $DB->delete_records(
                'local_chuspasocial_reaction',
                ['postid' => $postid]
            );

            // Eliminar los archivos asociados a la publicacion.
            get_file_storage()->delete_area_files(
                $context->id,
                'local_chuspasocial',
                'post',
                $postid
            );

            // Eliminar los tags de la publicacion.
            core_tag_tag::remove_all_item_tags(
                'local_chuspasocial',
                'local_chuspasocial_post',
                $postid
            );

            // Eliminar la publicacion.
            $DB->delete_records(
                'local_chuspasocial_post',
                ['id' => $postid]
            );

            // Preparar el evento de eliminacion.
            $event = \local_chuspasocial\event\post_deleted::create([
                'objectid' => $postid,
                'context' => $context,
                'userid' => $USER->id,
            ]);

            // Confirmar los cambios de la base de datos.
            $transaction->allow_commit();

        } catch (\Throwable $e) {
            // Revertir los cambios de la transaccion si hay un error.
            $transaction->rollback($e);
        }

        // Registrar el evento despues de confirmar la transaccion.
        $event->trigger();
    }
    
    /**
     * Crea una publicacion.
     *
     * @param string $content Contenido de la publicacion.
     * @param int $format Formato del contenido.
     * @param string $type Tipo de publicacion.
     * @param array $tags Tags de la publicacion.
     * @return int ID de la publicacion creada.
     */
    public static function create_post(string $content, int $format, string $type = 'post', array $tags = []): int {
        global $USER, $DB;

        $context = \context_system::instance();

        if ($type === 'announcement') {
            if (!has_capability('local/chuspasocial:postannouncement', $context)) {
                throw new \required_capability_exception($context, 'local/chuspasocial:postannouncement', 'nopermissions', '');
            }
        }

        $record = new \stdClass();
        $record->userid = $USER->id;
        $record->content = $content;
        $record->format = $format;
        $record->type = $type;
        $record->timecreated = time();
        $record->timemodified = time();

        $postid = $DB->insert_record('local_chuspasocial_post', $record);

        // Guardar los tags de la publicacion.
        core_tag_tag::set_item_tags(
            'local_chuspasocial',
            'local_chuspasocial_post',
            $postid,
            $context,
            $tags
        );

        return $postid;
    }

    /**
     * Indica si un usuario puede ver una publicacion.
     *
     * - courseid 0 (muro general): requiere local/chuspasocial:view en el sistema.
     * - courseid > 0: requiere estar inscrito o tener moodle/course:view.
     * - Si la publicacion esta oculta: solo el autor o quien tenga local/chuspasocial:moderate.
     *
     * @param post $post Publicacion a consultar.
     * @param int $userid Id del usuario que intenta verla.
     * @return bool
     */
    public static function can_view(post $post, int $userid): bool {
        $courseid = (int) $post->get('courseid');

        if ($courseid === 0) {
            // Muro general: se evalua en el contexto del sistema.
            $context = \context_system::instance();
            if (!has_capability('local/chuspasocial:view', $context, $userid)) {
                return false;
            }
        } else {
            // Publicacion de materia: solo inscritos o con acceso al curso.
            $context = \context_course::instance($courseid, IGNORE_MISSING);
            if (!$context) {
                return false;
            }
            if (!is_enrolled($context, $userid, '', true)
                    && !has_capability('moodle/course:view', $context, $userid)) {
                return false;
            }
        }

        // Publicacion oculta: solo el autor o un moderador.
        if ((int) $post->get('hidden') === 1) {
            if ((int) $post->get('userid') === $userid) {
                return true;
            }
            // La capability puede no estar definida todavia (igual que en delete_post()).
            return get_capability_info('local/chuspasocial:moderate')
                && has_capability('local/chuspasocial:moderate', $context, $userid);
        }

        return true;
    }
}
