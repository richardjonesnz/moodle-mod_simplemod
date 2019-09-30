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
        $exists = self::note_exists($data->simplemodid);

        if ($data->simplemodid) {
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

    public static function note_exists($simplemodid) {
        global $DB, $USER;

        return $DB->get_record('simplemod_notes', ['simplemodid' => $simplemodid, 'userid' => $USER->id], '*', IGNORE_MISSING);
    }

    public static function get_notes_list($simplemodid) {
        global $DB;

        $sql = "SELECT n.id, n.note, n.simplemodid, n.private, n.userid, u.firstname, u.lastname
                  FROM {simplemod_notes} n
                  JOIN {user} u ON u.id = n.userid
                 WHERE n.private = :private
                   AND n.simplemodid = :simplemodid";

        $records = $DB->get_records_sql($sql, ['private' => 0, 'simplemodid' => $simplemodid]);
        $data = array();
        foreach ($records as $record) {
            $row = array();
            $row['id'] = $record->id;
            $row['firstname'] = $record->firstname;
            $row['lastname'] = $record->lastname;
            $row['note'] = $record->note;
            $data[] = $row;
        }
        return $data;
    }
    public static function get_note_headers() {
        return [
            get_string('id', 'mod_simplemod'),
            get_string('firstname', 'mod_simplemod'),
            get_string('lastname', 'mod_simplemod'),
            get_string('note',  'mod_simplemod')];
    }
}