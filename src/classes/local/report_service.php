<?php

namespace local_chuspasocial\local;

defined('MOODLE_INTERNAL') || die();

/**
 * Servicio para la gestion de reportes.
 *
 * @package local_chuspasocial
 */
class report_service {

    /**
     * Marca un reporte como resuelto y oculta el contenido reportado.
     *
     * @param int $reportid ID del reporte a resolver.
     * @throws \required_capability_exception Si el usuario no es moderador.
     */
    public static function resolve_and_hide(int $reportid): void {
        global $DB, $USER;

        // Solo los moderadores pueden resolver reportes.
        require_capability('local/chuspasocial:moderate', \context_system::instance());

        // Buscar el reporte.
        $report = $DB->get_record(
            'local_chuspasocial_report',
            ['id' => $reportid],
            '*',
            MUST_EXIST
        );

         // Iniciar la transaccion.
        $transaction = $DB->start_delegated_transaction();

        try {
            // Marcar el reporte como resuelto.
            $report->status = 'resolved';
            $report->resolvedby = $USER->id;
            $report->timeresolved = time();
            $DB->update_record('local_chuspasocial_report', $report);

            // Ocultar el contenido reportado.
            if ($report->itemtype === 'post') {
                $DB->set_field(
                    'local_chuspasocial_post',
                    'hidden',
                    1,
                    ['id' => $report->itemid]
                );
            }

            // Confirmar los cambios de la base de datos.
            $transaction->allow_commit();
        } catch (\Throwable $e) {
            // Revertir los cambios si hay un error.
            $transaction->rollback($e);
        }
    }
}
