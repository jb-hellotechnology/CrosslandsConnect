<?php

include('../secrets.php');

include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$directory = strip_tags(addslashes($_POST['directory']));
$contact_id = strip_tags(addslashes($_POST['contact_id']));
	
$sql = "UPDATE contacts SET directory='".$directory."' WHERE CONTACT_ID='".$contact_id."'";
$params = array();
$result = $database->query($sql, $params);

?>