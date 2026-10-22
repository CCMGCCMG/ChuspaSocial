<?php
namespace local_chuspasocial\local;

/**
 * Pruebas unitarias para follow_service::follow_tag() y follow_service::unfollow_tag()
 *
 * @package    local_chuspasocial
 * @category   test
 * @covers     \local_chuspasocial\local\follow_service::follow_tag
 * @covers     \local_chuspasocial\local\follow_service::unfollow_tag
 */
class follow_service_test extends \advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest(true);
    }

    public function test_follow_tag_valido(): void {
        global $DB;

        $generator = $this->getDataGenerator();
        $user = $generator->create_user();
        $tag = $generator->create_tag(['rawname' => 'moodle']);
        $this->setUser($user);

        follow_service::follow_tag((int) $tag->id);

        $this->assertTrue($DB->record_exists('local_chuspasocial_follow', [
            'userid' => $user->id,
            'itemtype' => 'tag',
            'itemid' => $tag->id,
        ]));
    }

    public function test_unfollow_tag_valido(): void {
        global $DB;

        $generator = $this->getDataGenerator();
        $user = $generator->create_user();
        $tag = $generator->create_tag(['rawname' => 'moodle']);
        $this->setUser($user);

        follow_service::follow_tag((int) $tag->id);
        follow_service::unfollow_tag((int) $tag->id);

        $this->assertFalse($DB->record_exists('local_chuspasocial_follow', [
            'userid' => $user->id,
            'itemtype' => 'tag',
            'itemid' => $tag->id,
        ]));
    }

    public function test_unfollow_tag_inexistente(): void {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        // Dejar de seguir un tag que no existe no debe fallar ni crear datos.
        follow_service::unfollow_tag(999999);

        $this->assertEquals(0, $DB->count_records('local_chuspasocial_follow', [
            'userid' => $user->id,
            'itemtype' => 'tag',
        ]));
    }
}
