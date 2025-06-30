<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$url = $apiURL.'Contacts/365323017/Links';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
$response = curl_exec($ch);
$response = json_decode($response, true);
curl_close($ch);