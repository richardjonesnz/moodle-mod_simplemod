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
 * submissions class
 *
 * @package    mod_simplemod
 * @copyright  2019 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use \mod_simplemod\local\debugging;
namespace mod_simplemod\local;

defined('MOODLE_INTERNAL') || die();

class submissions {

    public static function save_note($data) {
        global $DB, $USER;

        // Check if user already has a note on this page.
        $exists = $DB->get_record('simplemod_notes', ['simplemodid' => $data->simplemodid, 'userid' => $USER->id], '*', IGNORE_MISSING);

        if ($exists) {
            $exists->timemodified = time();
            $exists->private = $data->private;
            $exists->note = $data->note;
            $DB->update_record('simplemod_notes', $exists);

        } else {
            $data->timecreated = time();
            $data->timemodified = time();
            $data->userid = $USER->id;
            $DB->insert_record('simplemod_notes', $data);
        }
    }
}