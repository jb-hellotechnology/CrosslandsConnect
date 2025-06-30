<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

if($_GET['p']==$password){

	$json = file_get_contents('php://input');
	$data = json_decode($json, true);
	
	$organisation = $data['entity'];
	
	if($organisation){
		
		$sql = "SELECT * FROM organisations WHERE ORGANISATION_ID=".$organisation['ORGANISATION_ID'];
		$params = array();
		$result = $database->query($sql, $params);
		
		if ($result->num_rows > 0) {
		
			$sql = "UPDATE organisations SET ORGANISATION_NAME='".addslashes($organisation['ORGANISATION_NAME'])."', PHONE='".$organisation['PHONE']."', WEBSITE='".$organisation['WEBSITE']."', ADDRESS_BILLING_STREET='".addslashes($organisation['ADDRESS_BILLING_STREET'])."', ADDRESS_BILLING_CITY='".addslashes($organisation['ADDRESS_BILLING_CITY'])."', ADDRESS_BILLING_STATE='".addslashes($organisation['ADDRESS_BILLING_STATE'])."', ADDRESS_BILLING_COUNTRY='".addslashes($organisation['ADDRESS_BILLING_COUNTRY'])."', ADDRESS_BILLING_POSTCODE='".addslashes($organisation['ADDRESS_BILLING_POSTCODE'])."'  WHERE ORGANISATION_ID=".$organisation['ORGANISATION_ID'];
			$params = array();
			
			mail('jack@hellotechnology.co.uk', 'Organisation Update Again', implode($organisation));
			
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

}