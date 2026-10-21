<?php 

namespace local_chuspasocial\local;

defined('MOODLE_INTERNAL') || die();

use advanced_testcase;

/**
 * Pruebas unitarias para el metodo can_post_announcement.
 * 
 * @package    local_chuspasocial
 * @category   test 
 * @covers     \local_chuspasocial\local\post_service::can_post_announcement 
 */
class post_service_test extends advanced_testcase {
    /**
     * Configuracion previa a cada test.
     */
    protected function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Test: Un docente puede publicar anuncios en su curso.
     */
    public function test_can_post_announcement_docente() {
        $generator = $this->getDataGenerator();
        
        $course = $generator->create_course();
        $user = $generator->create_user();
        $generator->enrol_user($user->id, $course->id, 'editingteacher');
        
        $this->setUser($user);

        $result = post_service::can_post_announcement($course->id);
        $this->assertTrue($result);
    }

    /**
     * Test: Un estudiante No puede publicar anuncios. 
     */
    public function test_can_post_announcement_estudiante(): void {
        $generator = $this->getDataGenerator();

        $course = $generator->create_course();
        $user = $generator->create_user();
        $generator->enrol_user($user->id, $course->id, 'student');

        $this->setUser($user);

        $result = post_service::can_post_announcement($course->id);
        $this->assertFalse($result);
    }

    /**
     * Test: Un administrador puede publicar anuncios en el muro general (site course / frontpage).
     */
    public function test_can_post_announcement_admin_muro_general(): void {
        global $SITE;

        $this->setAdminUser();

        $result = post_service::can_post_announcement($SITE->id);
        $this->assertTrue($result);
    }
}

