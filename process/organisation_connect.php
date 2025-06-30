<?php

include('../secrets.php');

include('../classes/database.class.php');

$database = new Database('localhost', $dbuser, $dbpass, $dbname);

$organisation_id = strip_tags(addslashes($_POST['organisation_id']));
	
$sql = 'SELECT * FROM organisations WHERE ORGANISATION_ID='.$organisation_id;
$params = array();
$result = $database->query($sql, $params);
$data = $result->fetch_array(MYSQLI_ASSOC);

echo '<h3>'.$data['ORGANISATION_NAME'].'</h3>';
if($data['ADDRESS_BILLING_STREET']){echo '<p><strong>Address</strong>:</p>';}
if($data['ADDRESS_BILLING_STREET']){echo '<p>';}
if($data['ADDRESS_BILLING_STREET']){echo $data['ADDRESS_BILLING_STREET'].'<br />';}
if($data['ADDRESS_BILLING_CITY']){echo $data['ADDRESS_BILLING_CITY'].'<br />';}
if($data['ADDRESS_BILLING_STATE']){echo $data['ADDRESS_BILLING_STATE'].'<br />';}
if($data['ADDRESS_BILLING_POSTCODE']){echo $data['ADDRESS_BILLING_POSTCODE'].'<br />';}
if($data['ADDRESS_BILLING_COUNTRY']){echo $data['ADDRESS_BILLING_COUNTRY'];}
if($data['ADDRESS_BILLING_STREET']){echo '</p>';}
if($data['PHONE']){echo '<p><strong>Phone:</strong> '.$data['PHONE'].'</p>';}
if($data['WEBSITE']){echo '<p><strong>Website:</strong> <a href="'.$data['WEBSITE'].'" target="_blank">'.$data['WEBSITE'].'</a></p>';}

echo '<div id="#connect_action"><button data-id="'.$organisation_id.'" id="connect">Request To Connect</button></div>';
?>