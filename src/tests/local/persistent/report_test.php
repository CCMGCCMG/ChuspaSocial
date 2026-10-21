<?php

namespace local_persistent;

use advanced_testcase;
use core\persistent;
use stdClass;

/**
 * Clase concreta de prueba para extender core/persistent.
 */
class dummy_persistent extends persistent {

    /** Nombre de la tabla ficticia */
    const TABLE = 'dummy_persistent';

    /**
     * Define la estructura de campos requerida por persistent.
     * 
     * @return array 
     */
    protected static function define_properties(): array {
        return [
            'name' => [
                'type' => PARAM_TEXT,
                'default' => '',
            ],
            'status' => [
                'type' => PARAM_INT,
                'default' => 0, 
            ],
        ];
    }
}

/**
 * Pruebas unitarias para las clases persistentes.
 * 
 * @package    local_persistent
 * @category   test
 * @covers     \core\persistent
 */
class report_test extends advanced_testcase {

    /**
     * Configuracion inicial antes de cada prueba.
     */
    protected function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Test 1: Creacion valida pasando el objeto de datos en el segundo argumento ($record).
     */
    public function test_creacion_valida(): void {
        $record = new stdClass();
        $record->name = 'Reporte de prueba';
        $record->status = 1;

        // Se pasa 0 como primer argumento ($id) y $record como el segundo argumento ($record)
        $persistent = new dummy_persistent(0, $record);

        $this->assertInstanceOf(persistent::class, $persistent);
        $this->assertEquals('Reporte de prueba', $persistent->get('name'));
    }

    /**
     * Test 2: Verificacion de valores por defecto al instanciar sin registro.
     */
    public function test_valores_por_defecto(): void {
        $persistent = new dummy_persistent(0);

        $this->assertEquals(0, $persistent->get('id'));
        $this->assertEquals('', $persistent->get('name'));
    }

    /**
     * Test 3: Validacion al intentar obtener o definir un campo invalido.
     */
    public function test_campo_invalido_lanza_excepcion(): void {
        $this->expectException(\coding_exception::class);

        $persistent = new dummy_persistent(0);
        $persistent->get('campo_inexistente_que_no_existe');
    }
}