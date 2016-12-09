<?php
if (!$ADMIN->locate('category_restrict'))
{
	$ADMIN->add('root', new admin_category('category_restrict', get_string('category_restrict', 'local_category_restrict')));
}
$ADMIN->add('category_restrict', new admin_externalpage('admin', get_string('manage_groups','local_category_restrict'), "$CFG->wwwroot/local/category_restrict/manage_groups.php"));
$ADMIN->add('category_restrict', new admin_externalpage('admin', get_string('category_restrict','local_category_restrict'), "$CFG->wwwroot/local/category_restrict/index.php"));
?>