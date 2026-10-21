<?php
namespace local_chuspasocial\local;

defined('MOODLE_INTERNAL') || die();

use moodle_exception;

class follow_service {

    public static function follow_user(int $userfollowedid): void {
        global $DB, $USER;

        if ($userfollowedid === (int) $USER->id) {
            throw new moodle_exception('cannotfollowself', 'local_chuspasocial');
        }

        $params = [
            'userid'       => $USER->id,
            'userfollowed' => $userfollowedid,
            'itemtype'     => 'user'
        ];

        if (!$DB->record_exists('local_chuspasocial_follows', $params)) {
            $record = (object)$params;
            $record->timecreated = time();
            $DB->insert_record('local_chuspasocial_follows', $record);

            $userfollowed = $DB->get_record('user', ['id' => $userfollowedid], '*', MUST_EXIST);

            $message = new \core\message\message();
            $message->component         = 'local_chuspasocial';
            $message->name              = 'newfollower';
            $message->userfrom          = $USER;
            $message->userto            = $userfollowed;
            $message->subject           = get_string('newfollowersubject', 'local_chuspasocial');
            $message->fullmessage       = get_string('newfollowerbody', 'local_chuspasocial');
            $message->fullmessageformat = FORMAT_MARKDOWN;
            $message->fullmessagehtml   = '';
            $message->smallmessage      = get_string('newfollowersmall', 'local_chuspasocial');
            $message->notification      = 1;

            message_send($message);
        }
    }
}