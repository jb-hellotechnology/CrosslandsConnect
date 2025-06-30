<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../secrets.php');

$url = $apiURL.'Contacts';

echo $url;

$data = array(
	"CONTACT_ID" 				=> "$_POST[contact_id]",
	"FIRST_NAME" 				=> "$_POST[first_name]",
	"LAST_NAME" 				=> "$_POST[last_name]",
	"PHONE"						=> "$_POST[phone]",
	"ADDRESS_MAIL_STREET" 		=> "$_POST[address_mail_street]", 
	"ADDRESS_MAIL_CITY" 		=> "$_POST[address_mail_city]", 
	"ADDRESS_MAIL_STATE" 		=> "$_POST[address_mail_state]", 
	"ADDRESS_MAIL_POSTCODE" 	=> "$_POST[address_mail_postcode]", 
	"ADDRESS_MAIL_COUNTRY" 		=> "$_POST[address_mail_country]" 
);

$data = json_encode($data);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);

// Handle authentication (you would need to implement this)
curl_setopt($curl, CURLOPT_USERPWD, $apiKey . ":");  

// Set options necessary for request.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

// Send request
$response = curl_exec($curl);
$response = json_decode($response);

return $response;

?>