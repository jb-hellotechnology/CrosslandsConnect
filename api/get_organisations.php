<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$page = $_GET['page'];

$skip = 250*$page;

$url = $apiURL.'Organisations?top=250&skip='.$skip;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
$response = curl_exec($ch);
$response = json_decode($response, true);
curl_close($ch);

foreach($response as $organisation){

	$sql = "SELECT * FROM organisations WHERE ORGANISATION_ID=".$organisation['ORGANISATION_ID'];
	$params = array();
	$result = $database->query($sql, $params);
	
	if ($result->num_rows > 0) {

		$sql = "UPDATE organisations SET ORGANISATION_NAME='".addslashes($organisation['ORGANISATION_NAME'])."', PHONE='".$organisation['PHONE']."', WEBSITE='".$organisation['WEBSITE']."', ADDRESS_BILLING_STREET='".addslashes($organisation['ADDRESS_BILLING_STREET'])."', ADDRESS_BILLING_CITY='".addslashes($organisation['ADDRESS_BILLING_CITY'])."', ADDRESS_BILLING_STATE='".addslashes($organisation['ADDRESS_BILLING_STATE'])."', ADDRESS_BILLING_COUNTRY='".addslashes($organisation['ADDRESS_BILLING_COUNTRY'])."', ADDRESS_BILLING_POSTCODE='".addslashes($organisation['ADDRESS_BILLING_POSTCODE'])."'  WHERE ORGANISATION_ID=".$organisation['ORGANISATION_ID'];
		$params = array();
		
		if ($database->query($sql, $params) === TRUE) {
			echo "Record updated successfully<br />";
		} else {
			echo "Error updating record: " . $mysqli->error;
		}
		
	} else {

		$sql = "INSERT INTO organisations (ORGANISATION_ID, ORGANISATION_NAME, PHONE, WEBSITE, ADDRESS_BILLING_STREET, ADDRESS_BILLING_CITY, ADDRESS_BILLING_STATE, ADDRESS_BILLING_COUNTRY, ADDRESS_BILLING_POSTCODE)
		VALUES ('".addslashes($organisation['ORGANISATION_ID'])."', '".addslashes($organisation['ORGANISATION_NAME'])."','".addslashes($organisation['PHONE'])."', '".addslashes($organisation['WEBSITE'])."', '".addslashes($organisation['ADDRESS_BILLING_STREET'])."', '".addslashes($organisation['ADDRESS_BILLING_CITY'])."', '".addslashes($organisation['ADDRESS_BILLING_STATE'])."', '".addslashes($organisation['ADDRESS_BILLING_COUNTRY'])."', '".addslashes($organisation['ADDRESS_BILLING_POSTCODE'])."')";
		$params = array();
		
		if ($database->query($sql, $params) === TRUE) {
			echo "New record created successfully<br />";
		} else {
			echo "Error: " . $sql . "<br>" . $mysqli->error;
		}
		
	}

}
