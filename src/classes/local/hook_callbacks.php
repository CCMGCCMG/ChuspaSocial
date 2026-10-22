<?php

namespace local_chuspasocial\local;

defined('MOODLE_INTERNAL') || die();

use core\hook\navigation\primary_extend;

/**
 * Callbacks de hooks de Moodle para ChuspaSocial.
 *
 * @package    local_chuspasocial
 */
class hook_callbacks {

    /**
     * Agrega el nodo "ChuspaSocial" a la navegacion principal.
     *
     * Solo se muestra a usuarios autenticados (no a invitados).
     *
     * @param primary_extend $hook Hook de extension de la navegacion principal.
     */
    public static function extend_primary_navigation(primary_extend $hook): void {
        if (!isloggedin() || isguestuser()) {
            return;
        }

        $primaryview = $hook->get_primaryview();

        $primaryview->add(
            get_string('pluginname', 'local_chuspasocial'),
            new \moodle_url('/local/chuspasocial/index.php'),
            \navigation_node::TYPE_CUSTOM,
            null,
            'chuspasocial'
        );

        // Enlace "Libros": solo si el marketplace está habilitado y el usuario tiene permiso.
        if (get_config('local_chuspasocial', 'enablemarketplace')
                && has_capability('local/chuspasocial:viewmarketplace', \context_system::instance())) {
            $primaryview->add(
                get_string('books', 'local_chuspasocial'),
                new \moodle_url('/local/chuspasocial/marketplace.php'),
                \navigation_node::TYPE_CUSTOM,
                null,
                'chuspasocial_books'
            );
        }
    }
}
