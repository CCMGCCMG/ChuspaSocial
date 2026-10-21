<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 the License, or
// (at your option) any later version.

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade steps for the Chuspasocial plugin.
 *
 * @package    local_chuspasocial
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Executes upgrade steps for the Chuspasocial plugin.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_local_chuspasocial_upgrade($oldversion): bool {
    return true;
}