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
    

	$gId = optional_param('gId', 0, PARAM_INT);
	
    
	$title = get_string('manage_groups','local_category_restrict');
    $PAGE->set_pagelayout('admin');
    $PAGE->set_url(new moodle_url('/local/category_restrict/manage_groups.php'));
    $PAGE->set_title($title);
    
	

	echo $OUTPUT->header();
	echo $OUTPUT->heading_with_help($title, 'category_restrict','local_category_restrict');



    if (!is_siteadmin()) {
        print_error('Access denied');
    }

	if(isset($_POST['updatebutton'])) {
		$message	= updateGroupMembers($_POST);
		echo "<h2> $message </h2>";
	}
	
	
	$groupUsers	=	get_group_users($gId);
	$potentialUsers	=	get_potential_users($gId);
	$allGroups	=	get_all_groups();
	   
	
	echo $OUTPUT->box_start();

?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

<script type="text/javascript">




	


	$().ready(function() {
	
		jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
			return this.each(function() {
				var select = this;
				var options = [];
				$(select).find('option').each(function() {
					options.push({value: $(this).val(), text: $(this).text()});
				});
				$(select).data('options', options);
				$(textbox).bind('change keyup', function() {
					var options = $(select).empty().data('options');
					var search = $.trim($(this).val());
					var regex = new RegExp(search,"gi");
				  
					$.each(options, function(i) {
						var option = options[i];
						if(option.text.match(regex) !== null) {
							$(select).append(
							   $('<option>').text(option.text).val(option.value)
							);
						}
					});
					if (selectSingleMatch === true && $(select).children().length === 1) {
						$(select).children().get(0).selected = true;
					}
				});            
			});
		};
		
		
		$(function() {
        	$('#select1').filterByText($('#search'), true);
        	$('#select2').filterByText($('#search1'), true);
    	});
		
	   $('#add').click(function() {
			return !$('#select1 option:selected').remove().appendTo('#select2');
	   });
	   $('#remove').click(function() {
			return !$('#select2 option:selected').remove().appendTo('#select1');
	   });
	   
	   $('form').submit(function() {
		 $('#select2 option').each(function(i) {
		  $(this).attr("selected", "selected");
		 });
	   });

	});
 </script>
<style>
.assignBtn {
    list-style: outside none none;
    margin-left: 35%;
	margin-top:200px;

}
</style>
<div class="table-responsive">


<form class="mform" id="mform1" method="post">
	<input type="hidden" name="edit" id="edit" value="<?php echo $Group->id;?>"  />
	<fieldset id="id_moodle" class="clearfix collapsible">
		
		<legend>Assign Members</legend>
		<div class="fcontainer clearfix" >
			<div id="fitem_id_groupname" class="fitem fitem_ftext  ">
				<div class="fitemtitle"><label for="id_groupname">Select Group </label></div>
				<div class="felement ftext"><select id="batch" required="required" name="batch">
                                      				<?php	echo "<option value=''>Select a Group</option>";

														
														
														foreach($allGroups as $Group){
															if(isset($gId) && $Group->id == $gId)
																echo "<option value='".$Group->id."' selected='selected'>$Group->group_name</option>";
															else
																echo "<option value='".$Group->id."'>$Group->group_name</option>";
														}
														?>
									</select></div>
			</div>
			
			<div class="row-fluid">
		<div class="span3">
		
		<legend><?php echo "Potential Users";?></legend>
			
				<div class="fitemtitle"><label for="id_groupname">Search </label></div>
				<div class="felement ftext"><input type="text" id="search" autocomplete="off"></div>
				<select name='potentialusers[]' id='select1' style='width:276px;height:300px; left:-6px' multiple >
				  
				<?php
				
					
					
				
				foreach($potentialUsers as $user)
				{
					echo '<option value="'.$user->id.'">'.ucfirst(strtolower( $user->firstname)).' '.ucfirst(strtolower($user->lastname)).'</option>';
				}
				
				?>

				</select>
		</div>
		
		<div class="span3">
			<ul class="assignBtn">
			<li><a href="#" id="add">
					  <input type="button" style="font-weight:bold; width:80px; padding:5px;" value="&gt;&gt;" id="savechanges2" name="Add" />
					</a>
			</li>
			<li><a href="#" id="remove">
					<input type="button"  style="font-weight:bold; width: 80px; padding:5px;" name="Remove" id="savechanges" value="&lt;&lt;" />
				</a>
			</li>
            
			</ul>
		</div>
		
		<div class="span3">
				<legend><?php echo "Group Users";?></legend>
					<div class="fitemtitle"><label for="id_groupname">&nbsp; </label></div>		
					<div class="felement ftext"><input type="hidden" id="search1" autocomplete="off"></div>
					<select name='groups[]' id='select2' style='width:276px;height:300px;left:-6px' multiple>

						<?php  
						
							foreach($groupUsers as $user)
							{
								echo '<option value="'.$user->id.'">'.ucfirst(strtolower( $user->firstname)).' '.ucfirst(strtolower($user->lastname)).'</option>';
							}
						?>

					</select>
		</div>
		 
</div>
	
	</fieldset>
	
	<fieldset>
		
		<div id="fgroup_id_buttonar" class="fitem fitem_actionbuttons fitem_fgroup "><div class="felement fgroup"><input name="updatebutton" value="Update Group" id="id_submitbutton" type="submit"> <input name="cancel" value="Cancel" onclick="skipClientValidation = true; return true;" class=" btn-cancel" id="id_cancel" type="submit"></div></div>
		
	</fieldset>
		
</form>
 

   
   </div>


  

<?php
	
	echo $OUTPUT->box_end();

    echo $OUTPUT->footer();
?>