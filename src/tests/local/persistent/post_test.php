<?php
namespace local_chuspasocial\local\persistent;

defined('MOODLE_INTERNAL') || die();

use advanced_testcase;
use local_chuspasocial\local\persistent\post;

/**
 * Pruebas unitarias para la clase persistente post.
 *
 * @package    local_chuspasocial
 * @category   test
 * @covers     \local_chuspasocial\local\persistent\post
 */
class post_test extends advanced_testcase {

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
        $record = new \stdClass();
        $record->userid = 1;
        $record->content = 'Publicación de prueba en el muro';
        $record->format = 1;

        // Se pasa 0 como primer argumento ($id) y $record como el segundo argumento
        $post = new post(0, $record);

        $this->assertInstanceOf(post::class, $post);
        $this->assertEquals('Publicación de prueba en el muro', $post->get('content'));
    }

    /**
     * Test 2: Verificacion de valores por defecto al instanciar sin registro.
     */
    public function test_valores_por_defecto(): void {
        $post = new post(0);

        // En persistent, el id por defecto antes de guardar es 0
        $this->assertEquals(0, $post->get('id'));
    }

    /**
     * Test 3: Validacion al intentar obtener un campo invalido.
     */
    public function test_campo_invalido_lanza_excepcion(): void {
        $this->expectException(\coding_exception::class);

        $post = new post(0);
        $post->get('campo_inexistente_que_no_existe');
    }
}
