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
    
	$title = get_string('manage_groups','local_category_restrict');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_url(new moodle_url('/local/category_restrict/manage_groups.php'));
    $PAGE->set_title($title);
    
	

	echo $OUTPUT->header();
	echo $OUTPUT->heading_with_help($title, 'category_restrict','local_category_restrict');



    if (!is_siteadmin()) {
        print_error('Access denied');
    }

	if(isset($_POST['submitbutton'])) {
		$message	= insertGroups($_POST);
		echo "<h2> $message </h2>";
	}
	
	
	
	if(isset($_POST['updatebutton'])) {
		$message	= updateGroup($_POST);
		echo "<h2> $message </h2>";
	}
	
	
	if($edit > 0) {
		$Group	=	getGroupById($edit);
	}
	if($delete > 0) {
		
		$Group	=	deleteGroup($delete);
	}
	
	$rsGroups = $DB->get_records('local_cr_groups');
	
	echo $OUTPUT->box_start();

?>
<?php if ($edit > 0) {?>
<form class="mform" id="mform1" method="post">
	<input type="hidden" name="edit" id="edit" value="<?php echo $Group->id;?>"  />
	<fieldset id="id_moodle" class="clearfix collapsible">
		
		<legend>Update Group</legend>
		<div class="fcontainer clearfix" >
			<div id="fitem_id_username" class="fitem fitem_ftext  "><div class="fitemtitle"><label for="id_groupname">Group Name </label></div>
			<div class="felement ftext"><input size="20" name="group_name" id="id_groupname" type="text" value="<?php echo $Group->group_name;?>"></div></div>
		</div>
	
	</fieldset>
	
	<fieldset>
		
		<div id="fgroup_id_buttonar" class="fitem fitem_actionbuttons fitem_fgroup "><div class="felement fgroup"><input name="updatebutton" value="Update Group" id="id_submitbutton" type="submit"> <input name="cancel" value="Cancel" onclick="skipClientValidation = true; return true;" class=" btn-cancel" id="id_cancel" type="submit"></div></div>
		
	</fieldset>
		
</form>
<?php } else {?>
<form class="mform" id="mform1" method="post">
	<fieldset id="id_moodle" class="clearfix collapsible">
		
		<legend>Add New Group</legend>
		<div class="fcontainer clearfix" >
			<div id="fitem_id_username" class="fitem fitem_ftext  "><div class="fitemtitle"><label for="id_groupname">Group Name </label></div>
			<div class="felement ftext"><input size="20" name="group_name" id="id_groupname" type="text"></div></div>
		</div>
	
	</fieldset>
	
	<fieldset>
		
		<div id="fgroup_id_buttonar" class="fitem fitem_actionbuttons fitem_fgroup "><div class="felement fgroup"><input name="submitbutton" value="Add Group" id="id_submitbutton" type="submit"> <input name="cancel" value="Cancel" onclick="skipClientValidation = true; return true;" class=" btn-cancel" id="id_cancel" type="submit"></div></div>
		
	</fieldset>
		
</form>
<?php } ?>


  <h2> Manage Groups </h2>                                                                               
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Group Name</th>
        <th>Group Size</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
	<?php $i=1;if(count($rsGroups) > 0) {
		foreach ($rsGroups as $Group) {
	?>
      <tr>
        <td><?php echo $i++;?></td>
        <td><?php echo $Group->group_name;?></td>
        <td><?php echo get_group_size($Group->id);?></td>
        <td><a title="Delete" href="?del=<?php echo $Group->id;?>"><img src="<?php echo $OUTPUT->pix_url('t/delete');?>" alt="Delete" class="iconsmall"></a> 
		<a title="Edit" href="?edit=<?php echo $Group->id;?>"><img src="<?php echo $OUTPUT->pix_url('t/edit');?>" alt="Edit" class="iconsmall"></a>
		<a title="Assign" href="assign_members.php?gId=<?php echo $Group->id;?>"><img src="<?php echo $OUTPUT->pix_url('i/user');?>" alt="Assign" class="iconsmall"></a>
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
