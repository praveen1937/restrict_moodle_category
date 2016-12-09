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
    
    $gId = optional_param('group', 0, PARAM_INT);
	$cId = optional_param('cat', 0, PARAM_INT);
	
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

	if($gId > 0 && $cId >0) {
		$Courses =	get_courses_by_cat($cId);
	}
	
	
?>
<script src="jquery.min.js"></script>
<link href="multiple-select.css" rel="stylesheet" />
<link href="base.css" rel="stylesheet" />
<script src="multiple-select.js"></script>

<form method="post" action="">
  <div class="form-group">
    <label for="exampleInputEmail1">Select Group</label>
    <select id="group" required="required" name="group">
                                      				<?php	echo "<option value=''>Select a Group</option>";

														foreach($allGroups as $Group){
															if(isset($gId) && $Group->id == $gId)
																echo "<option value='".$Group->id."' selected='selected'>$Group->group_name</option>";
															else
																echo "<option value='".$Group->id."'>$Group->group_name</option>";
														}
														?>
									</select>
    
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Select Category</label>
    <select id="cat" required="required" name="cat">
                                      				<?php	echo "<option value=''>Select a Category</option>";

														foreach($allCats as $Cat){
															if(isset($cId) && $Cat->id == $cId)
																echo "<option value='".$Cat->id."' selected='selected'>$Cat->name</option>";
															else
																echo "<option value='".$Cat->id."'>$Cat->name</option>";
														}
														?>
									</select>
    
  </div>
  
  <div class="form-group">
    <label for="exampleSelect1">Select Course / Resources</label>
    	<select multiple="multiple" style="width:300px" name="test[]" id="courses">
        <?php foreach($Courses as $Course){?>
        <optgroup label="<?php echo $Course->id.":".$Course->fullname;?>">
            <?php echo display_course_modules($Course->id);?>
            <option value="1">Forum - Test</option>
            <option value="2">Course Ref link</option>
            <option value="3">Third Resource</option>
            <option value="4">Fourth Resource</option>
        </optgroup>
        <?php } ?>
        
    </select>

  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
        $("#courses").multipleSelect({
            filter: true,
           
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
		
		$('#group').on('change', function() {
  			var group = this.value;
			var cat = $('#cat').val();
			location.href="?group="+group+"&cat="+cat;
		})
		$('#cat').on('change', function() {
  			var cat = this.value;
			var group =$('#group').val();
			alert('thiss'+group);
			location.href="?group="+group+"&cat="+cat;
		})
    </script>
	
<?php
	
	echo $OUTPUT->box_end();

    echo $OUTPUT->footer();
?>
