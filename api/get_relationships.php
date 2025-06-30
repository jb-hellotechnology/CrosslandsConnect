<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$page = $_GET['page'];

$skip = 250*$page;

$url = $apiURL.'Relationships';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
$response = curl_exec($ch);
$response = json_decode($response, true);
curl_close($ch);

$sql = "TRUNCATE relationships";
$params = array();
$result = $database->query($sql, $params);

foreach($response as $relationship){
	
	//print_r($relationship);
	
	$sql = "INSERT INTO relationships (RELATIONSHIP_ID, FORWARD_TITLE, FORWARD, REVERSE_TITLE, REVERSE, FOR_CONTACTS, FOR_ORGANISATIONS)
	VALUES ('".$relationship['RELATIONSHIP_ID']."', '".$relationship['FORWARD_TITLE']."', '".$relationship['FORWARD']."', '".$relationship['REVERSE_TITLE']."', '".$relationship['REVERSE']."', '".$relationship['FOR_CONTACTS']."', '".$relationship['FOR_ORGANISATIONS']."')";
	$params = array();
	
	if ($database->query($sql, $params) === TRUE) {
		echo "New record created successfully<br />";
	} else {
		echo "Error: " . $sql . "<br>" . $mysqli->error;
	}
	
}
