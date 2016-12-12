<?php

/**
 * Library of useful functions
 * @copyright 2013 Bruno Sampaio
 * @package core
 * @subpackage institutionaaq
 */

defined('MOODLE_INTERNAL') || die;


function insertGroups($Param) {
	global $DB;
	$groupName	= $Param['group_name'];
	
	if($DB->record_exists('local_cr_groups', array('group_name' => $groupName))) {
		return ' Record Exists !';
	} else {
		$record = new stdClass();
		$record->group_name	=	$groupName;
		$lastinsertid = $DB->insert_record('local_cr_groups', $record, false);
		return 'Group Added';
	}

}

function get_group_size($gId) {
	global $DB;
	//echo $gId; exit;
	$result = $DB->count_records('local_cr_group_members', array('group_id' => $gId));
	return $result;
}

function getGroupById($gId) {
	global $DB;
	//echo $gId; exit;
	$result = $DB->get_record('local_cr_groups', array('id' => $gId));
	return $result;
}
function updateGroup($Param) {
	global $DB;
	$groupName	= $Param['group_name'];
	$groupId	= $Param['edit'];
	
	
	$sql = "update {local_cr_groups} set group_name = '$groupName' where id = '$groupId'";
//	exit;
	$DB->execute($sql);
	return 'Group Updated';

}
function deleteGroup($groupId) {
	global $DB;
	
	
	
	$sql = "delete from {local_cr_groups} where id = '$groupId'";
//	exit;
	$DB->execute($sql);
	return 'Group Updated';

}
function get_group_users($gId) {
	global $DB;
	//echo $gId; exit;
	if($gId>0) {
		$sql = "select * from {user} a left join {local_cr_group_members} b on a.id = b.user_id where a.deleted = 0 and a.id = b.user_id and b.group_id = '$gId'";
		$result = $DB->get_records_sql($sql);
		return $result;
	} else {
		return NULL;
	}
}

function get_potential_users($gId) {
	global $DB;
	//echo $gId; exit;
	if($gId>0) {
		$sql = "select * from {user} where id not in (select user_id from {local_cr_group_members} where group_id = '$gId') and deleted = 0 ";
		
	} else {
		$sql = "select * from {user} where deleted = 0 ";
	}
	$result = $DB->get_records_sql($sql);
	return $result;
}
function get_all_groups() {
	global $DB;
	$result = $DB->get_records('local_cr_groups');
	return $result;
}
function updateGroupMembers($Param) {
	global $DB;
	$gId	= $Param['batch'];
	$groupMembers = $Param['groups'];
	
	$DB->delete_records('local_cr_group_members', array('group_id' => $gId));
	
	foreach($groupMembers as $user) {
		$record = new stdClass();
		$record->group_id	=	$gId;
		$record->user_id	=	$user;
		//echo $gId.'=>'.$user;echo "<br>";
		
		$lastinsertid = $DB->insert_record('local_cr_group_members', $record, false);
	}

	
	return 'Group Updated';
	

}
function get_all_category() {
	global $DB;
	
	$result = $DB->get_records('course_categories');
	return $result;
}
function get_courses_by_cat($cat) {
	global $DB;
	$result = $DB->get_records('course', array('category'=>$cat));
	return $result;
}
function display_course_modules($csId) {
	global $DB;
	
	$result = $DB->get_records('course_modules', array('course'=>$csId));
	foreach($result as $Module) {
	
		$module_name = $DB->get_field_sql("select name from {modules} c where c.id = ".$Module->module."");
	
		 $sql = "SELECT cm.id as cm_id, m.name as cm_name, md.name AS mod_type FROM {course_modules} cm JOIN {modules} md ON md.id = cm.module JOIN {".$module_name."} m ON m.id = cm.instance $sectionjoin WHERE cm.id = ".$Module->id." AND md.name = '$module_name'";
	
    		$rsMod =  $DB->get_record_sql($sql, $params, $strictness);
			echo "<option value='".$rsMod->cm_id."'>$rsMod->cm_name</option>";
	}
}
function get_module_name($Module) {
	global $DB;
	//$result = get_fast_modinfo($Module->course);
}
