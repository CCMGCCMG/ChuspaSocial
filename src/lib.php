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
 * Lib functions for local_chuspasocial.
 *
 * @package    local_chuspasocial
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adds the «Wall» link to the navigation of the course.
 *
 * The link goes to course.php, the wall of the course, and it is only added
 * when the user has the local/chuspasocial:view capability in the course.
 *
 * @param navigation_node $navigation The course navigation node.
 * @param stdClass $course The course.
 * @param context_course $context The context of the course.
 * @return void
 */
function local_chuspasocial_extend_navigation_course(
    navigation_node $navigation,
    stdClass $course,
    context_course $context
): void {
    if (!has_capability('local/chuspasocial:view', $context)) {
        return;
    }

    $url = new moodle_url('/local/chuspasocial/course.php', ['id' => $course->id]);
    $navigation->add(
        get_string('wall', 'local_chuspasocial'),
        $url,
        navigation_node::TYPE_SETTING,
        null,
        'local_chuspasocial_wall',
        new pix_icon('i/group', '')
    );
}
