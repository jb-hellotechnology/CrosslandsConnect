<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$page = $_GET['page'];

$skip = 250*$page;

$url = $apiURL.'Contacts?brief=true&count_total=true&top=250&skip='.$skip;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
$response = curl_exec($ch);
$response = json_decode($response, true);
curl_close($ch);

foreach($response as $contact){
	
	$sql = "SELECT * FROM contacts WHERE CONTACT_ID=".$contact['CONTACT_ID'];
	$params = array();
	$result = $database->query($sql, $params);
	
	if ($result->num_rows > 0) {
		// output data of each row
		$sql = "UPDATE contacts SET FIRST_NAME='".$contact['FIRST_NAME']."', LAST_NAME='".$contact['LAST_NAME']."', EMAIL_ADDRESS='".$contact['EMAIL_ADDRESS']."', PHONE='".$contact['PHONE']."', ORGANISATION_ID='".$contact['ORGANISATION_ID']."' WHERE CONTACT_ID=".$contact['CONTACT_ID'];
		echo $sql."<br />";
		$params = array();
		
		if ($database->query($sql, $params) === TRUE) {
			echo "Record updated successfully<br />";
		} else {
			echo "Error updating record: " . $mysqli->error;
		}
	} else {
		$sql = "INSERT INTO contacts (CONTACT_ID, FIRST_NAME, LAST_NAME, EMAIL_ADDRESS, PHONE, ORGANISATION_ID)
		VALUES ('".$contact['CONTACT_ID']."', '".$contact['FIRST_NAME']."', '".$contact['LAST_NAME']."', '".$contact['EMAIL_ADDRESS']."', '".$contact['PHONE']."', ORGANISATION_ID='".$contact['ORGANISATION_ID']."')";
		$params = array();
		
		if ($database->query($sql, $params) === TRUE) {
			echo "New record created successfully<br />";
		} else {
			echo "Error: " . $sql . "<br>" . $mysqli->error;
		}
	}
	
}
