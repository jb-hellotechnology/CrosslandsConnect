<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../secrets.php');

include('../classes/database.class.php');
include('../classes/crosslands.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);
$crosslands = new Crosslands($database);

$email = strip_tags(addslashes($_POST['email']));
$first_name = strip_tags(addslashes($_POST['first_name']));
$last_name = strip_tags($_POST['last_name']);

$crosslands->create_contact($apiKey, $apiURL, $email, $first_name, $last_name);

?>