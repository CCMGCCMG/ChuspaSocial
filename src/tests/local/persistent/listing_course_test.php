<?php

namespace local_persistent;

use advanced_testcase;
use core\persistent;
use stdClass;

class listing_course extends persistent {
    const TABLE = 'local_chuspasocial_lstcourse';

    protected static function define_properties(): array {
        return [
            'listingid' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'courseid' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
        ];
    }
}
/**
 * Pruebas para la clase persistente de vínculos entre publicaciones y cursos.
 *
 * @package local_persistent
 * @category test
 * @covers \core\persistent
 */

class listing_course_test extends advanced_testcase {
    protected function setUp(): void {
        $this->resetAfterTest();
    }

    public function test_creacion_valida(): void {
        $record = new stdClass();
        $record->listingid = 10;
        $record->courseid = 20;

        $persistent = new listing_course(0, $record);

        $this->assertInstanceOf(persistent::class, $persistent);
        $this->assertEquals(10, $persistent->get('listingid'));
        $this->assertEquals(20, $persistent->get('courseid'));
    }

    public function test_valores_por_defecto(): void {
        $persistent = new listing_course(0);

        $this->assertEquals(0, $persistent->get('id'));
        $this->assertEquals(0, $persistent->get('listingid'));
        $this->assertEquals(0, $persistent->get('courseid'));
    }

    public function test_campo_invalido_lanza_excepcion(): void {
        $this->expectException(\coding_exception::class);

        $persistent = new listing_course(0);
        $persistent->get('campo_inexistente');
    }
}