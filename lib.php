<?php
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
	$DB->execute($sql);
	
	$sqlUser = "delete from {local_cr_group_members} where group_id = '$groupId'";
	$DB->execute($sqlUser);
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
function get_courses_select_box($catId, $gId) {
	global $DB;
	$select ='';
	$Courses = $DB->get_records('course', array('category'=>$catId));
	
	$CategoryChecked = check_selected($catId, 'category', $gId);
	
	$ip = "-";
	$select .= '<select multiple="multiple" style="width:300px" name="multiCourse" id="multiCourse">';
        foreach($Courses as $Course){
			$CourseChecked = check_selected($Course->id, 'course', $gId);
        	$select .= "<optgroup label='".$Course->id.$ip.$Course->fullname."'>";
            $select .= display_course_modules($Course->id,$CategoryChecked,$CourseChecked,$gId);
           
        	$select .= "</optgroup>";
        } 
        
    $select .= "</select>";
	
	return $select;
}
//$restrictId => catId / CourseId / Module ID $type = category/course/module
function check_selected($restrictId, $type, $group) {
	global $DB;
	
	if($DB->record_exists('local_cr', array('restrict_id' => $restrictId, 'restrict_type' => $type,  'group_id' => $group))) {
		return true;
	} else {
		return false;
	}
}
function get_courses_by_cat($cat) {
	global $DB;
	$result = $DB->get_records('course', array('category'=>$cat));
	return $result;
}
function display_course_modules($csId,$CategoryChecked,$CourseChecked,$gId) {
	global $DB;
	$option = '';
	$result = $DB->get_records('course_modules', array('course'=>$csId));
	if(count($result) > 0) {
		foreach($result as $Module) {
			
			$module_name = $DB->get_field_sql("select name from {modules} c where c.id = ".$Module->module."");
		
			 $sql = "SELECT cm.id as cm_id, m.name as cm_name, md.name AS mod_type FROM {course_modules} cm JOIN {modules} md ON md.id = cm.module JOIN {".$module_name."} m ON m.id = cm.instance $sectionjoin WHERE cm.id = ".$Module->id." AND md.name = '$module_name'";
		
				$rsMod =  $DB->get_record_sql($sql, $params, $strictness);
				$ModuleChecked = check_selected($rsMod->cm_id, 'module', $gId);
				if($CategoryChecked==true || $CourseChecked == true || $ModuleChecked == true) {
					$option .= "<option value='".$rsMod->cm_id."' selected='selected'>$rsMod->cm_id - $rsMod->cm_name</option>";
				} else {
					$option .= "<option value='".$rsMod->cm_id."'>$rsMod->cm_id - $rsMod->cm_name</option>";
				}
		}
	} else {
		if($CategoryChecked==true || $CourseChecked == true || $ModuleChecked == true) {
			$option .= "<option value='0' selected='selected'>All Future Modules</option>";
		} else {
			$option .= "<option value='0'>All Future Modules</option>";
		}
	}
	return $option;
}
function insert_multiple_course($Param) {
	global $DB;
	
	$groupId	= $Param['group'];
	$catId		= $Param['cat'];
	$courseCatId	= $Param['catCourseIds'];
	
	//Delete Records
	$msg = delete_multiple_course($groupId,$catId);
	
	if(trim($courseCatId) == 'All') {
		insert_cr_help($groupId,$catId,$catId,'category',0);
	} else {
		$coursesArr = explode("=>",$courseCatId);
		foreach($coursesArr as $Courses) {
			$courseContent = explode(':',$Courses,2);
			
			$course = $courseContent[0];
			$content ='';
			if(count($courseContent)==2) {
				$content = $courseContent[1];
			}
		
			list($courseId, $courseName) = explode('-',$course,2);
			$courseId = str_replace('[','',$courseId);
			if($content=='') {
				insert_cr_help($groupId,$catId,$courseId,'course',$catId);
			} else {
				$Modules = explode(',',$content);
				foreach($Modules as $Module) {
					list($modId, $modName) = explode('-',$Module,2);
					insert_cr_help($groupId,$catId,$modId,'module',$courseId);
				}
			}
		}
	}
	return "Courses updated";
}
function insert_cr_help($groupId,$catId,$resId,$resType,$parentId) {
	global $DB;
	
	
		$record = new stdClass();
		$record->group_id	=	$groupId;
		$record->category_id	=	$catId;
		$record->restrict_id	=	$resId;
		$record->restrict_type	=	$resType;
		$record->parent_id	=	$parentId;
		
		$lastinsertid = $DB->insert_record('local_cr', $record, false);
}
function get_groups_cats($cId,$gId) {
	global $DB;
	
		$sqlWhere ="where ";
		$sql='';
		if($gId>0) {
			$sql = "a.group_id = '$gId' ";
		}
		if($cId>0) {
			if($sql=='') {
				$sql = "a.category_id = '$cId'";
			} else {
				$sql = "and a.category_id = '$cId'";
			}
		}
		if($sql!='') {
			$sql = $sqlWhere.$sql;
		}
		
		
		
		$sqlNew = "select * from mdl_local_cr a group by concat(a.group_id, a.category_id)";
		
		$result =  $DB->get_records_sql($sqlNew);
		
		return $result;
	
}
function get_group_name($gId) {
	global $DB;
	$group_name = $DB->get_field_sql("select group_name from {local_cr_groups} where id = '$gId'");
	return $group_name;
}
function get_cat_name($cId) {
	global $DB;
	$cat_name = $DB->get_field_sql("select name from {course_categories} where id = '$cId'");
	return $cat_name;
}
function delete_multiple_course($groupId,$catId) {
	global $DB;
	$sql = "delete from {local_cr} where group_id = '$groupId' and category_id = '$catId'";
	$DB->execute($sql);
	return "Records Deleted!";
}
function get_category_sorted($uId) {
	global $DB;
	//echo $gId; exit;
	$sql="select * from {course_categories} $con order by sortorder"; 
     $res=$DB->get_records_sql($sql);
	 
	
	 $groupId = get_group_id_by_user(6);
	
	 if($groupId > 0) {
	 	$res = trim_category($res,$groupId);
	 }
	
}
function trim_category($Result,$gId) {
	foreach($Result as $key => $res) {
		if(check_selected($res->id, 'category', $gId) ==  true) {
			unset($Result[$key]);
		} 
	}
	if(count($Result) > 0) {
		$Result = array_values($Result);
	}
	return array_values($Result);
}
function get_group_id_by_user($uId) {
	global $DB;
	$group_id = $DB->get_field_sql("select group_id from {local_cr_group_members} where user_id = '$uId'");
	if($group_id!= NULL && $group_id > 0) {
		return $group_id;
	} else {
		return 0;
	}
}
