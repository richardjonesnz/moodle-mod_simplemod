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
 * For student submissions
 *
 * @package    mod_simplemod
 * @copyright  2019 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_simplemod
 * @see https://github.com/justinhunt/moodle-mod_simplemod
 */

require_once('../../config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir . '/formslib.php');

/**
 * Define a form for adding a user note to the notes table.
 */
class simplemod_submission_form extends moodleform {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('textarea', 'note', get_string('note', 'mod_simplemod'),
                ['rows' => 5, 'cols' => 40]);
        $mform->addElement('advcheckbox', 'private', null,
                get_string('private', 'mod_simplemod'), null, [0, 1]);
        $mform->setType('note', PARAM_TEXT);

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->setType('id', PARAM_INT);
        $this->add_action_buttons();

    }
}

// We need the course module id (id) or
// the simplemod instance id (n).
$id = optional_param('id', 0, PARAM_INT);
$n  = optional_param('n', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('simplemod', $id, 0, false,
            MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $simplemod = $DB->get_record('simplemod', ['id' => $cm->instance], '*', MUST_EXIST);
} else if ($n) {
    $simplemod = $DB->get_record('simplemod', ['id' => $n], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $simplemod->course], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('simplemod', $simplemod->id,
            $course->id, false, MUST_EXIST);
}

// Print the page header.
$PAGE->set_url('/mod/simplemod/submission.php', ['id' => $cm->id]);
require_login($course, true, $cm);
$PAGE->set_title(format_string($simplemod->name));
$viewpage = new moodle_url('/mod/simplemod/view.php', ['id' => $cm->id]);
// Process the form:

$mform = new simplemod_submission_form(null, ['id' => $cm->id]);

// Cancelled, redirect to view.
if ($mform->is_cancelled()) {
    redirect($viewpage, get_string('cancelled'), 2);
}

// The renderer performs output to the page.
$renderer = $PAGE->get_renderer('mod_simplemod');

// Call the renderer method to display the simplemod intro content.
$renderer->render_submission_page_content($mform);
