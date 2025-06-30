<?php
include('classes/database.class.php');
include('classes/crosslands.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);
$crosslands = new Crosslands($database);

// Import the Composer Autoloader to make the SDK classes accessible:
require 'vendor/autoload.php';

// Now instantiate the Auth0 class with our configuration:
$auth0 = new \Auth0\SDK\Auth0([
	'domain' => $AUTH0_DOMAIN,
	'clientId' => $AUTH0_CLIENT_ID,
	'clientSecret' => $AUTH0_CLIENT_SECRET,
	'cookieSecret' => $AUTH0_COOKIE_SECRET
]);

$session = $auth0->getCredentials();

if($session){
	// GET CONTACT
	$contact = $crosslands->get_contact($session->user['email']);
	if($contact['ORGANISATION_ID']){
		$organisation = $crosslands->get_organisation($contact['ORGANISATION_ID']);
	}

	if(!$contact){
		// CREATE INSIGHTLY CONTACT
		header("location:/create");
	}
	
	// UPDATE TAGS
	$tagsUpdated = $crosslands->tags_updated($contact['CONTACT_ID']);
	$timediff = strtotime(date('Y-m-d H:i:s')) - strtotime($tagsUpdated);
	
	if($timediff > 86400){ 
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
		
		$crosslands->tags_update($contact['CONTACT_ID']);
	}
	
	// UPDATE RELATIONSHIPS
	$relationshipsUpdated = $crosslands->relationships_updated($contact['CONTACT_ID']);
	$timediff = strtotime(date('Y-m-d H:i:s')) - strtotime($relationshipsUpdated);
	
	if($timediff > 86400){ 
		$url = $apiURL.'Contacts/'.$contact['CONTACT_ID'].'/Links';
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
		$response = curl_exec($ch);
		$response = json_decode($response, true);
		curl_close($ch);
		
		$sql = "DELETE FROM contact_relationships WHERE CONTACT_ID=".$contact['CONTACT_ID'];
		$params = array();
		$database->query($sql, $params);
		
		foreach($response as $relationship){
			if($relationship['RELATIONSHIP_ID']){
				if(!$relationship['IS_FORWARD']){
					$relationship['IS_FORWARD'] = 0;
				}
				$sql = "INSERT INTO contact_relationships (CONTACT_ID, RELATIONSHIP_ID, IS_FORWARD, DETAILS, ROLE, LINK_ID, OBJECT_NAME, OBJECT_ID, LINK_OBJECT_NAME, LINK_OBJECT_ID) VALUES ('".$contact['CONTACT_ID']."', '".$relationship['RELATIONSHIP_ID']."', '".$relationship['IS_FORWARD']."', '".$relationship['DETAILS']."', '".$relationship['ROLE']."', '".$relationship['LINK_ID']."', '".$relationship['OBJECT_NAME']."', '".$relationship['OBJECT_ID']."', '".$relationship['LINK_OBJECT_NAME']."', '".$relationship['LINK_OBJECT_ID']."')";
				$params = array();
				$database->query($sql, $params);
			}
		}
		
		$crosslands->tags_update($contact['CONTACT_ID']);
	}
}
?>
<!doctype html>
<html>
<head>
  <title>Crosslands Connect</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@200" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/assets/slick/slick.css"/>
  <link href="/assets/css/style.css?v=<?= rand() ?>" rel="stylesheet">
  
  <link rel="manifest" href="/manifest.json?v=<?= rand() ?>" />
  <!-- ios support -->
  <link rel="apple-touch-icon" href="/assets/icons/icon-72x72.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-96x96.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-128x128.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-144x144.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-152x152.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-192x192.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-384x384.png" />
  <link rel="apple-touch-icon" href="/assets/icons/icon-512x512.png" />
  <meta name="apple-mobile-web-app-status-bar" content="#26292b" />
  <meta name="theme-color" content="#26292b" />
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
   integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
   crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
   integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
   crossorigin=""></script>
  
</head>
<body>
	<header>
		<a href="https://crosslands.training" target="_blank"><img src="https://crosslands.training/crosslands/wp-content/themes/crosslands/images/logo.png" alt="Crosslands Logo" /></a>
		<nav>
			<?php
			if($session){
			?>
			<ul class="mobile-hide">
				<li><a href="/">Dashboard</a></li>
				<li><a href="https://learn.crosslands.training/" target="_blank">Learning Platform</a></li>
			</ul>
			<?php
			}
			?>		
		</nav>
		<?php 
		if ($session === null) {
			echo '<a class="action" href="/process/login">Login <span class="material-symbols-outlined">login</span></a>';
		}else{
		?>
		<button id="settings">
			<span class="mobile-hide">
				Settings
				<span class="material-symbols-outlined">
					settings
				</span>
			</span>
			<span class="mobile-show">
				Menu
				<span class="material-symbols-outlined">
					menu
				</span>
			</span>
		</button>
		<ul class="settings">
			<li class="mobile-show"><a href="/">Dashboard</a></li>
			<li class="mobile-show"><a href="https://learn.crosslands.training/" target="_blank">Learning Platform</a></li>
			<li><a href="/profile">My Profile</a></li>
			<li><a href="/church">My Church</a></li>
			<li><a href="/support">Get Support</a></li>
			<li><a href="/process/logout">Logout</a></li>
		</ul>
		<?php
		}
		?>
	</header>