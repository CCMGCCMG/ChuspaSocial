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
 * Service to query the book listings of the marketplace.
 *
 * @package    local_chuspasocial
 * @copyright  2026 SIS-INF
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_chuspasocial\local;

/**
 * Service to query the book listings of the marketplace.
 *
 * @package    local_chuspasocial
 */
class listing_service {
    /**
     * Search listings by book title or author, case insensitive.
     *
     * The search text is always passed as a SQL parameter, never concatenated.
     *
     * @param string $q Text to search in the title or the author.
     * @param int $page Page number, starting at 0.
     * @param int $perpage Number of listings per page.
     * @return array List of listing records, newest first.
     */
    public static function search(string $q, int $page, int $perpage): array {
        global $DB;

        $titlelike = $DB->sql_like('l.title', ':title', false);
        $authorlike = $DB->sql_like('l.author', ':author', false);
        $pattern = '%' . $DB->sql_like_escape(trim($q)) . '%';

        $sql = "SELECT l.*
                  FROM {local_chuspasocial_listing} l
                 WHERE $titlelike OR $authorlike
              ORDER BY l.timecreated DESC, l.id DESC";
        $params = ['title' => $pattern, 'author' => $pattern];

        return array_values($DB->get_records_sql($sql, $params, $page * $perpage, $perpage));
    }

    /**
     * Save the photos of a listing from the draft area of the form.
     *
     * The files are stored in the 'listingphoto' file area, using the
     * listing id as item id. It must be called when a listing is created
     * and when it is updated.
     *
     * @param int $listingid Id of the listing that owns the photos.
     * @param int $draftitemid Draft area id of the filemanager element.
     */
    public static function save_photos(int $listingid, int $draftitemid): void {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        $context = \context_system::instance();
        file_save_draft_area_files(
            $draftitemid,
            $context->id,
            'local_chuspasocial',
            'listingphoto',
            $listingid,
            ['subdirs' => 0, 'accepted_types' => ['image']]
        );
    }
}
