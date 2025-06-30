<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

if($_GET['p']==$password){

	$json = file_get_contents('php://input');
	$data = json_decode($json, true);
	
	$contact = $data['entity'];
	
	if($contact){
		
		$sql = "SELECT * FROM contacts WHERE CONTACT_ID=".$contact['CONTACT_ID'];
		$params = array();
		$result = $database->query($sql, $params);
		
		if ($result->num_rows > 0) {
			// output data of each row
			$sql = "UPDATE contacts SET FIRST_NAME='".$contact['FIRST_NAME']."', LAST_NAME='".$contact['LAST_NAME']."', EMAIL_ADDRESS='".$contact['EMAIL_ADDRESS']."', PHONE='".$contact['PHONE']."', ORGANISATION_ID='".$contact['ORGANISATION_ID']."' WHERE CONTACT_ID=".$contact['CONTACT_ID'];
			$params = array();
			
			if ($database->query($sql, $params) === TRUE) {
				echo "Record updated successfully<br />";
			} else {
				echo "Error updating record: " . $database->error;
			}
		} else {
			$sql = "INSERT INTO contacts (CONTACT_ID, FIRST_NAME, LAST_NAME, EMAIL_ADDRESS, PHONE, ORGANISATION_ID)
			VALUES ('".$contact['CONTACT_ID']."', '".$contact['FIRST_NAME']."', '".$contact['LAST_NAME']."', '".$contact['EMAIL_ADDRESS']."', '".$contact['PHONE']."', '".$contact['ORGANISATION_ID']."')";
			$params = array();
			
			if ($database->query($sql, $params) === TRUE) {
				echo "New record created successfully<br />";
			} else {
				echo "Error: " . $sql . "<br>" . $database->error;
			}
		}
	
	}

	$url = $apiURL.'Contacts/'.$contact['CONTACT_ID'].'/Tags';
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
	$response = curl_exec($ch);
	$response = json_decode($response, true);
	curl_close($ch);
	
	$sql = "DELETE FROM tags WHERE CONTACT_ID=".$contact['CONTACT_ID'];
	$params = array();
	$database->query($sql, $params);
	
	foreach($response as $tag){
		$sql = "INSERT INTO tags (CONTACT_ID, TAG_NAME) VALUES ('".$contact['CONTACT_ID']."', '".$tag['TAG_NAME']."')";
		$params = array();
		$database->query($sql, $params);
	}

}

