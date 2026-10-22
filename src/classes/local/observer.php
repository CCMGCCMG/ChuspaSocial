<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observers of ChuspaSocial.
 *
 * @package    local_chuspasocial
 * @copyright  2026 SIS-INF
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_chuspasocial\local;

use core\message\message;
use core_user;
use moodle_url;

/**
 * Event observers of ChuspaSocial, registered in db/events.php.
 *
 * @package    local_chuspasocial
 */
class observer {
    /**
     * Send the private gradepublished notification to the graded student.
     *
     * Only the graded student receives the notification, and only when the grade
     * is visible to the student. This observer never creates posts.
     *
     * @param \core\event\user_graded $event The event.
     * @return void
     */
    public static function user_graded(\core\event\user_graded $event): void {
        $studentid = (int) $event->relateduserid;
        if (empty($studentid)) {
            return;
        }

        $grade = $event->get_grade();
        if ($grade->finalgrade === null || $grade->is_hidden()) {
            return;
        }

        $course = get_course($event->courseid);
        $coursename = format_string($course->fullname, true, ['context' => \context_course::instance($course->id)]);
        $subject = get_string('messageprovider:gradepublished_subject', 'local_chuspasocial', $coursename);
        $url = new moodle_url('/grade/report/user/index.php', ['id' => $course->id]);

        $message = new message();
        $message->component = 'local_chuspasocial';
        $message->name = 'gradepublished';
        $message->userfrom = core_user::get_noreply_user();
        $message->userto = $studentid;
        $message->subject = $subject;
        $message->fullmessage = $subject;
        $message->fullmessageformat = FORMAT_PLAIN;
        $message->fullmessagehtml = '';
        $message->smallmessage = $subject;
        $message->notification = 1;
        $message->contexturl = $url->out(false);
        $message->contexturlname = $coursename;
        $message->courseid = $course->id;

        message_send($message);
    }
}
