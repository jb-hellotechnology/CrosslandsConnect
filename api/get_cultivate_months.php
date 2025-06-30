<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$page = $_GET['page'];

$url = $apiURL.'Cultivate_month__c';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
$response = curl_exec($ch);
$response = json_decode($response, true);
curl_close($ch);

$sql = "TRUNCATE cultivate_months";
$params = array();
$database->query($sql, $params);

foreach($response as $object){
	
	$sql = "INSERT INTO cultivate_months (RECORD_NAME, deadline, reading, tutorial_date, tutorial_link)
	VALUES ('".addslashes($object['RECORD_NAME'])."', '".addslashes($object['CUSTOMFIELDS'][0]['FIELD_VALUE'])."','".addslashes($object['CUSTOMFIELDS'][1]['FIELD_VALUE'])."','".addslashes($object['CUSTOMFIELDS'][2]['FIELD_VALUE'])."','".addslashes($object['CUSTOMFIELDS'][3]['FIELD_VALUE'])."')";
	$params = array();
	$database->query($sql, $params);
	
}
