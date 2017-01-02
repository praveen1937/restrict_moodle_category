<?php
defined('MOODLE_INTERNAL') || die;
function insertImage() {
	global $DB, $CFG;
	$countryCode	= $_POST['country'];
	
	if($DB->record_exists('local_theme_background', array('country_name' => $countryCode))) {
		return ' Record Exists. Please use different Country !';
	} else {
		
		$target_dir = "uploads/";
		$target_file = $target_dir .time(). basename($_FILES["image"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
	
		// Check file size
		if ($_FILES["image"]["size"] > 500000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
				$record = new stdClass();
				$record->country_name	=	$countryCode;
				$record->background_image	=	$CFG->wwwroot.'/local/theme_background/'.$target_file;
				$lastinsertid = $DB->insert_record('local_theme_background', $record, false);
				return 'Record Added';
			} else {
				echo "Sorry, there was an error uploading your file.";
				$uploadOk = 0;
			}
		}	
	
	}
		
}

function getAllCountries() {
	global $DB;
	//echo $gId; exit;
	$country = get_string_manager()->get_list_of_countries();
	return array_merge(array('Default'=>'Default'),$country);
}


function getCountryById($gId) {
	global $DB;
	//echo $gId; exit;
	$result = $DB->get_record('local_theme_background', array('id' => $gId));
	return $result;
}
function updateImage() {
	global $DB, $CFG;;
	
	$editId	= $_POST['edit'];
	
		
		
			$target_dir = "uploads/";
			$target_file = $target_dir .time(). basename($_FILES["image"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["image"]["tmp_name"]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
		
			// Check file size
			if ($_FILES["image"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
					
					$fileTargetName = $CFG->wwwroot.'/local/theme_background/'.$target_file;
					$sql = "update {local_theme_background} set background_image = '$fileTargetName' where id = '$editId'"; 
					$DB->execute($sql);
					
					return 'Image Updated!';
				} else {
					echo "Sorry, there was an error uploading your file.";
					$uploadOk = 0;
				}
			}	
		
	
	
	//return 'Group Updated';
}
function deleteCountry($countrId) {
	global $DB;
	
	
	
	$sql = "delete from {local_theme_background} where id = '$countrId'";
	$DB->execute($sql);
	
	
	return 'Recard Deleted!';
}
