<?php
if (!$ADMIN->locate('theme_background'))
{
	$ADMIN->add('root', new admin_category('theme_background', get_string('theme_background', 'local_theme_background')));
}
$ADMIN->add('theme_background', new admin_externalpage('admin', get_string('theme_background','local_theme_background'), "$CFG->wwwroot/local/theme_background/manage_groups.php"));
?>
