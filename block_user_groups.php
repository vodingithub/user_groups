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
 * Block for user cohort list.
 *
 * @package   block_user_groups
 * @copyright 2017 onwards USPTU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_user_groups extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_user_groups');
    }

    function has_config() {
        return false;
    }

    function applicable_formats() {
        return array('all' => true);
    }
    

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $CFG;
		global $DB;
        

        if ($this->content !== NULL) {
            return $this->content;
        }
       

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
		$this->content->text = '';
	if($muid=optional_param('id',NULL, PARAM_INT)){ 
		$courseswithactivitycounts = $DB->get_records_sql(
			'select mc.name as cohort, mcc.name as catname
				FROM {user} mu
				LEFT JOIN {cohort_members} mcm ON mcm.userid=mu.id
				LEFT JOIN {cohort} mc ON mc.id=mcm.cohortid
				LEFT JOIN {context} mc1 ON mc.contextid=mc1.id
				LEFT JOIN {course_categories} mcc ON mcc.id=mc1.instanceid
				WHERE mu.id=:userid
				and mu.deleted=0',
				array('userid' =>$muid ));
      
		$this->content->text='<div><ul>';
		foreach($courseswithactivitycounts as $coh ){
			if($coh->catname)$catname=$coh->catname; else $catname='System';
			$this->content->text.='<li> '.$coh->cohort.' ('.$catname.')</li>';
		}
		$this->content->text.='</ul></div>';
   
	}
        
        return $this->content;
    }
    
 

}
