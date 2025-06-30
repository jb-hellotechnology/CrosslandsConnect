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

if(!$session){
	header("location:/");
}
?>
<!doctype html>
<html>
<head>
  <title>Crosslands Portal</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/assets/slick/slick.css"/>
  <link href="/assets/css/style.css?v=<?= rand() ?>" rel="stylesheet">
  
  <link rel="manifest" href="/manifest.json" />
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
  
</head>
<body>
	<header>
		<a href="/"><img src="https://crosslands.training/crosslands/wp-content/themes/crosslands/images/logo.png" alt="Crosslands Logo" /></a>
		<nav>
			<?php
			if($session){
			?>
			<ul class="mobile-hide">
				<li><a href="/">Dashboard</a></li>
				<li><a href="/profile">Profile</a></li>
			</ul>
			<button class="mobile-show" id="menu">
				Menu
				<span class="material-symbols-outlined">
					menu
				</span>
			</button>
			<ul class="mobile-show">
				<li><a href="/">Dashboard</a></li>
				<li><a href="/profile">Profile</a></li>
				<li><a href="/process/logout">Logout</a></li>
			</ul>
			<?php
			}
			?>		
		</nav>
		<?php 
		if ($session === null) {
			echo '<a class="action" href="/process/login">Login <span class="material-symbols-outlined">login</span></a>';
		}else{
			echo '<a class="action mobile-hide" href="/process/logout">Logout <span class="material-symbols-outlined">logout</span></a>';
		}
		?>
	</header>