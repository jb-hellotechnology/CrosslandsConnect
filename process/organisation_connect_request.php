<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../secrets.php');

include('../classes/database.class.php');
include('../classes/crosslands.class.php');

$database = new Database('localhost', $dbuser, $dbpass, $dbname);
$crosslands = new Crosslands($database);

// Import the Composer Autoloader to make the SDK classes accessible:
require '../vendor/autoload.php';

// Now instantiate the Auth0 class with our configuration:
$auth0 = new \Auth0\SDK\Auth0([
	'domain' => $AUTH0_DOMAIN,
	'clientId' => $AUTH0_CLIENT_ID,
	'clientSecret' => $AUTH0_CLIENT_SECRET,
	'cookieSecret' => $AUTH0_COOKIE_SECRET
]);

$session = $auth0->getCredentials();
$contact = $crosslands->get_contact($session->user['email']);

$organisation_id = strip_tags(addslashes($_POST['organisation_id']));
	
$sql = 'SELECT * FROM organisations WHERE ORGANISATION_ID='.$organisation_id;
$params = array();
$result = $database->query($sql, $params);
$data = $result->fetch_array(MYSQLI_ASSOC);

// Multiple recipients
$to = 'fiona.parker@crosslands.training'; // note the comma

// Subject
$subject = 'Church Connection Request';

// Message
$message = '
<html>
<head>
  <title>Portal: Church Connection Request</title>
</head>
<body>
  <p><strong>Portal: Church Connection Request</strong></p>
  <p><strong>Contact:</strong> '.$contact['FIRST_NAME'].' '.$contact['LAST_NAME'].'</p>
  <p><strong>Contact Email:</strong> '.$session->user['email'].'</p>
  <p><strong>Requests to Join:</strong> '.$data['ORGANISATION_NAME'].', '.$data['ADDRESS_BILLING_STREET'].'</p>
  <p>If you believe this request to be legitimate please update '.$contact['FIRST_NAME'].'\'s organisation in Insightly and inform them that the request has been approved.</p>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Additional headers
$headers[] = 'From: Crosslands Portal <no-reply@my.crosslands.training>';

// Mail it
mail($to, $subject, $message, implode("\r\n", $headers));

echo '<p class="alert success"><strong>Success</strong><br />Your request has been sent and will be processed by one of our team shortly</p>';
?>