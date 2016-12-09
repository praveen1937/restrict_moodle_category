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
// along with Moodle.  If not, see <htt00p://www.gnu.org/licenses/>.

//  Livetek Software Consulting Services custom code
//  Coder: Deepali Gujarathi
//  Contact: info@livetek.co.in
//  Date: 18 March 2013
//
//  Description: Allows admin to enrol one or more users into multiple courses at the same time.
//  Using this plugin allows admin to manage course enrolments from one screen itself

/**
 * Multiple Enrollments - Allows admin to enrol one or more users into multiple courses at the same time.
 *                        There is a single screen which allows admin to manage course enrolments.
 *
 * @package      local
 * @subpackage   multiple_enrollments
 * @maintainer   Livetek Software Consulting Services
 * @author       Deepali Gujarathi
 * @contact      info@livetek.co.in
 * @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

	require_once('../../config.php');
    require_once('lib.php');
    
    
	$title = get_string('category_restrict','local_category_restrict');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_url(new moodle_url('/local/category_restrict/index.php'));
    $PAGE->set_title($title);
    
	

	echo $OUTPUT->header();
	echo $OUTPUT->heading_with_help($title, 'category_restrict','local_category_restrict');

    if (!is_siteadmin()) {
        print_error('Access denied');
    }

	$allGroups	=	get_all_groups();
	$allCats	=	get_all_category();
	echo $OUTPUT->box_start();

?>
<script src="jquery.min.js"></script>
<link href="multiple-select.css" rel="stylesheet" />
<link href="base.css" rel="stylesheet" />
<script src="multiple-select.js"></script>

<form method="post" action="">
  <div class="form-group">
    <label for="exampleInputEmail1">Select Group</label>
    <select id="batch" required="required" name="batch">
                                      				<?php	echo "<option value=''>Select a Group</option>";

														foreach($allGroups as $Group){
																echo "<option value='".$Group->id."'>$Group->group_name</option>";
														}
														?>
									</select>
    
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Select Category</label>
    <select id="cat" required="required" name="cat">
                                      				<?php	echo "<option value=''>Select a Category</option>";

														foreach($allCats as $Cats){
																echo "<option value='".$Cats->id."'>$Cats->name</option>";
														}
														?>
									</select>
    
  </div>
  
  <div class="form-group">
    <label for="exampleSelect1">Select Course / Resources</label>
    	<select multiple="multiple" style="width:300px" name="test[]" id="courses">
        <optgroup label="First Course ">
            <option value="1">Forum - Test</option>
            <option value="2">Course Ref link</option>
            <option value="3">Third Resource</option>
            <option value="4">Fourth Resource</option>
        </optgroup>
        <optgroup label="Second Course">
            <option value="11">210</option>
            <option value="12">321</option>
            <option value="13">432</option>
            <option value="14">543</option>
            <option value="15">654</option>
            <option value="16">765</option>
            <option value="17">876</option>
            <option value="18">987</option>
            <option value="19">098</option>
        </optgroup>
        <optgroup label="Third Course">
            <option value="20">012</option>
            <option value="21">123</option>
            <option value="22">234</option>
            <option value="23">345</option>
            <option value="24">456</option>
            <option value="25">567</option>
            <option value="26">678</option>
            <option value="27">789</option>
            <option value="28">890</option>
        </optgroup>
    </select>

  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
        $("#courses").multipleSelect({
            filter: true,
            multiple: true,
        });
		$("#getSelectsBtn").click(function() {
            alert("Selected values: " + $("select").multipleSelect("getSelects"));
            alert("Selected texts: " + $("select").multipleSelect("getSelects", "text"));
        });
		$("#getSelectsBtn1").click(function() {
            $("select").multipleSelect("checkAll");
        });
		function updateCourse(){
		 	alert("Selected texts: " + $("select").multipleSelect("getSelects", "text"));
			 $("#selVal").val($("select").multipleSelect("getSelects", "text"));
			 return true;
		}
    </script>
	
<?php
	
	echo $OUTPUT->box_end();

    echo $OUTPUT->footer();
?>