
<?php

namespace local_chuspasocial\local;

defined('MOODLE_INTERNAL') || die();

/**
 * Servicio para la gestion de reportes de contenido.
 *
 * @package    local_chuspasocial
 */
class report_service {

    /**
     * Registra un reporte sin duplicarlo para el mismo usuario.
     *
     * Si el usuario ya reporto el mismo elemento, devuelve el ID
     * del reporte existente.
     *
     * @param string $itemtype Tipo de contenido: post, comment o listing.
     * @param int $itemid ID del contenido reportado.
     * @param string $reason Motivo del reporte.
     * @return int ID del reporte creado o existente.
     * @throws \moodle_exception Si los parametros no son validos.
     */
    public static function create_report(
        string $itemtype,
        int $itemid,
        string $reason
    ): int {
        global $DB, $USER;

        $validtypes = ['post', 'comment', 'listing'];

        if (!in_array($itemtype, $validtypes, true)) {
            throw new \moodle_exception(
                'invalidparameter',
                'error',
                '',
                'itemtype'
            );
        }

        if ($itemid <= 0) {
            throw new \moodle_exception(
                'invalidparameter',
                'error',
                '',
                'itemid'
            );
        }

        $reason = trim($reason);

        if ($reason === '') {
            throw new \moodle_exception(
                'invalidparameter',
                'error',
                '',
                'reason'
            );
        }

        // Comprobar si el usuario ya reporto este contenido.
        $conditions = [
            'itemtype' => $itemtype,
            'itemid' => $itemid,
            'reporterid' => $USER->id,
        ];

        $existing = $DB->get_record(
            'local_chuspasocial_report',
            $conditions,
            'id'
        );

        if ($existing) {
            return (int) $existing->id;
        }

        // Preparar el reporte.
        $report = new \stdClass();
        $report->itemtype = $itemtype;
        $report->itemid = $itemid;
        $report->reporterid = $USER->id;
        $report->reason = $reason;
        $report->status = 'open';
        $report->timecreated = time();

        // Insertar el reporte y devolver su ID.
        return (int) $DB->insert_record(
            'local_chuspasocial_report',
            $report
        );
    }
}
