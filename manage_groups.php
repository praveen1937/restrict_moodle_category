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
    

	$edit = optional_param('edit', 0, PARAM_INT);
	$delete = optional_param('del', 0, PARAM_INT);
    
	$title = get_string('manage_groups','local_theme_background');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_url(new moodle_url('/local/theme_background/manage_groups.php'));
    $PAGE->set_title($title);
    
	

	echo $OUTPUT->header();
	echo $OUTPUT->heading_with_help($title, 'theme_background','local_theme_background');



    if (!is_siteadmin()) {
        print_error('Access denied');
    }

	if(isset($_POST['submitbutton'])) {
		$message	= insertImage();
		echo "<h2> $message </h2>";
	}
	
	
	
	if(isset($_POST['updatebutton'])) {
		$message	= updateImage();
		echo "<h2> $message </h2>";
	}
	
	
	if($edit > 0) {
		$BackgroundImage	=	getCountryById($edit);
	}
	if($delete > 0) {
		
		$Group	=	deleteCountry($delete);
	}
	
	$rsImages = $DB->get_records('local_theme_background');
	
	$Countries =  getAllCountries();
	
	
	
	echo $OUTPUT->box_start();

?>
<?php if ($edit > 0) {?>
<form class="mform" id="mform1" method="post" enctype="multipart/form-data">
	<input type="hidden" name="edit" id="edit" value="<?php echo $BackgroundImage->id;?>"  />
    <input type="hidden" name="hdnImage" id="hdnImage" value="<?php echo $BackgroundImage->background_image;?>"  />
	<fieldset id="id_moodle" class="clearfix collapsible">
		
		<legend>Update Background Image : <?php echo $Countries[$BackgroundImage->country_name];?> </legend>
		<div class="fcontainer clearfix" >
			<div id="fitem_id_username" class="fitem fitem_ftext  "><div class="fitemtitle"><label for="id_groupname">Select Image </label></div>
			<div class="felement ftext"> <input type="file" name="image" id="image"> &nbsp;&nbsp; <img src="<?php echo $BackgroundImage->background_image;?>" height="50" width="50"></div></div>
		</div>
	
	</fieldset>
	
	<fieldset>
		
		<div id="fgroup_id_buttonar" class="fitem fitem_actionbuttons fitem_fgroup "><div class="felement fgroup"><input name="updatebutton" value="Update" id="id_submitbutton" type="submit"> <input name="cancel" value="Cancel" onclick="skipClientValidation = true; return true;" class=" btn-cancel" id="id_cancel" type="submit"></div></div>
		
	</fieldset>
		
</form>
<?php } else {?>
<form class="mform" id="mform1" method="post" enctype="multipart/form-data">
	<fieldset id="id_moodle" class="clearfix collapsible">
		
		<legend>Add New Country Background Image</legend>
		<div class="fcontainer clearfix" >
			<div id="fitem_id_username" class="fitem fitem_ftext  "><div class="fitemtitle"><label for="id_groupname">Country Name </label></div>
			<div class="felement ftext"><select id="country" required="required" name="country">
                                      				<?php	echo "<option value=''>Select a Country</option>";
														
														foreach($Countries as $CountryCode => $CountryName){
																echo "<option value='".$CountryCode."'>$CountryName</option>";
														}
														?>
									</select></div></div>
		</div>
        <div class="fcontainer clearfix" >
			<div id="fitem_id_username" class="fitem fitem_ftext  "><div class="fitemtitle"><label for="id_groupname">Select Image </label></div>
			<div class="felement ftext"> <input type="file" name="image" id="image"></div></div>
		</div>
	
	</fieldset>
	
	<fieldset>
		
		<div id="fgroup_id_buttonar" class="fitem fitem_actionbuttons fitem_fgroup "><div class="felement fgroup"><input name="submitbutton" value="Add" id="id_submitbutton" type="submit"> <input name="cancel" value="Cancel" onclick="skipClientValidation = true; return true;" class=" btn-cancel" id="id_cancel" type="submit"></div></div>
		
	</fieldset>
		
</form>
<?php } ?>


  <h2> Manage Groups </h2>                                                                               
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Country Name</th>
        <th>Background Image</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
	<?php $i=1;if(count($rsImages) > 0) {
		foreach ($rsImages as $Image) {
	?>
      <tr>
        <td><?php echo $i++;?></td>
        <td><?php echo $Countries[$Image->country_name];?></td>
        <td><img src="<?php echo $Image->background_image;?>" height="50" width="50"></td>
        <td><a title="Delete" href="?del=<?php echo $Image->id;?>"><img src="<?php echo $OUTPUT->pix_url('t/delete');?>" alt="Delete" class="iconsmall"></a> 
		<a title="Edit" href="?edit=<?php echo $Image->id;?>"><img src="<?php echo $OUTPUT->pix_url('t/edit');?>" alt="Edit" class="iconsmall"></a>
		</td>
      </tr>
	  <?php } }?>
    </tbody>
  </table>
  </div>

<?php
	
	echo $OUTPUT->box_end();

    echo $OUTPUT->footer();
?>
