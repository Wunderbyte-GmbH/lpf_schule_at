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
 * The mod_qcreate question regraded event.
 *
 * @package    mod_qcreate
 * @copyright  2014 Jean-Michel Vedrine
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_qcreate\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_qcreate question regraded event class.
 *
 * @property-read array $other {
 *      Extra information about event.
 *
 *      - int qcreateid: the id of the qcreate.
 * }
 *
 * @package    core
 * @since      Moodle 2.7
 * @copyright  2014 Jean-Michel Vedrine
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_regraded extends \core\event\base {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['objecttable'] = 'qcreate_grades';
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventquestionregraded', 'mod_qcreate');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' regraded the question with id '" . $this->other['questionid'] . "' " .
            "created by the user with id '$this->relateduserid' for the qcreate with course module id '$this->contextinstanceid'.";
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/grades.php', array('id' => $this->other['qcreateid'],
            'user' => $this->relateduserid));
    }

    /**
     * Return the legacy event log data.
     *
     * @return array
     */
    protected function get_legacy_logdata() {
        return array($this->courseid, 'qcreate', 'manualgrade', 'comment.php?id=' . $this->other['qcreateid'] .
            '&user=' . $this->relateduserid, $this->other['qcreateid'], $this->contextinstanceid);
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();

        if (!isset($this->relateduserid)) {
            throw new \coding_exception('The \'relateduserid\' must be set.');
        }

        if (!isset($this->other['qcreateid'])) {
            throw new \coding_exception('The \'qcreateid\' value must be set in other.');
        }

        if (!isset($this->other['questionid'])) {
            throw new \coding_exception('The \'questionid\' value must be set in other.');
        }
    }
}
