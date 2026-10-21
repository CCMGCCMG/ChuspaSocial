<?php
namespace local_chuspasocial\event;

defined('MOODLE_INTERNAL') || die();

use advanced_testcase;

/**
 * Pruebas unitarias para el evento post_deleted.
 *
 * @package    local_chuspasocial
 * @category   test
 * @covers     \local_chuspasocial\event\post_deleted
 */
class post_deleted_test extends advanced_testcase {

    /**
     * Configuracion inicial antes de cada prueba.
     */
    protected function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Test: Eliminar una publicacion dispara exactamente un evento post_deleted
     * con el objectid correcto.
     */
    public function test_se_dispara_evento_post_deleted(): void {
        global $DB;

        // Prevenir que el rollback deshaga los eventos.
        $this->preventResetByRollback();

        // Redirigir los eventos para poder capturarlos.
        $this->redirectEvents();

        // Crear un usuario de prueba.
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        // Crear un post de prueba directamente en la base de datos.
        $postid = $DB->insert_record('local_chuspasocial_post', (object) [
            'userid' => $user->id,
            'courseid' => 0,
            'content' => 'Publicacion de prueba para eliminacion',
            'contentformat' => 1,
            'timemodified' => time(),
            'timecreated' => time(),
        ]);

        // Llamar al metodo delete_post del servicio.
        \local_chuspasocial\local\post_service::delete_post($postid);

        // Capturar los eventos disparados.
        $events = $this->getEvents();

        // Filtrar solo los eventos post_deleted.
        $postdeletedevents = array_filter($events, function($e) {
            return $e instanceof post_deleted;
        });

        // Verificar que se disparo exactamente un evento post_deleted.
        $this->assertCount(1, $postdeletedevents);

        // Obtener el primer evento.
        $event = reset($postdeletedevents);

        // Verificar que el objectid del evento coincide con el ID del post eliminado.
        $this->assertEquals($postid, $event->objectid);
    }
}
