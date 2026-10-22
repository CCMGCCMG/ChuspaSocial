<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Service to query the comments of the ChuspaSocial posts.
 *
 * @package    local_chuspasocial
 * @copyright  2026 SIS-INF
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_chuspasocial\local;

/**
 * Service to query the comments of the ChuspaSocial posts.
 *
 * @package    local_chuspasocial
 */
class comment_service {
    /**
     * Get the comments of a post, oldest first.
     *
     * Returns an empty array when the post does not exist or is hidden.
     *
     * @param int $postid ID of the post to get the comments from.
     * @return array List of comment records, oldest first.
     */
    public static function get_comments(int $postid): array {
        global $DB;

        // The comments are only visible when the post is visible.
        $post = $DB->get_record('local_chuspasocial_post', ['id' => $postid], 'id, hidden');
        if (!$post || !empty($post->hidden)) {
            return [];
        }

        return array_values($DB->get_records(
            'local_chuspasocial_comment',
            ['postid' => $postid],
            'timecreated ASC, id ASC'
        ));
    }
    
    /**
     * Add a comment to a post and notify the post author.
     *
     * @param int $postid ID of the post being commented on.
     * @param int $userid ID of the user creating the comment.
     * @param string $content Text content of the comment.
     * @return int The ID of the newly created comment record.
     */
    public static function add_comment(int $postid, int $userid, string $content): int {
        global $DB;
        $post = $DB->get_record('local_chuspasocial_post', [ 'id' => $postid ], 'id, userid');
        if (!$post) {
            throw new \moodle_exception('invalidpostid', 'local_chuspasocial');
        }

        $comment = new \stdClass();
        $comment->postid = $postid;
        $comment->userid = $userid;
        $comment->content = $content;
        $comment->timecreated = time();
        
        $commentid = $DB->insert_record('local_chuspasocial_comment', $comment);
        if ($post->userid != $userid) {
            $userfrom = \core_user::get_user($userid, '*', MUST_EXIST);
            $userto = \core_user::get_user($post->userid, '*', MUST_EXIST);

            $message = new \core\message\message();
            $message->component = 'local_chuspasocial';
            $message->name = 'newcomment';
            $message->userfrom = $userfrom;
            $message->userto = $userto;
            $message->subject = 'Nuevo comentario en tu publicación';
            $message->fullmessage = 'Alguien ha comentado tu publicación en ChuspaSocial.';
            $message->fullmessageformat = FORMAT_PLAIN;
            $message->smallmessage = 'Nuevo comentario en tu publicación.';
            $message->notification = 1;
            message_send($message);
        }

        return $commentid;
    }
}
