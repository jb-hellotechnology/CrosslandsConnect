<?php

include('secrets.php');

// Import the Composer Autoloader to make the SDK classes accessible:
require 'vendor/autoload.php';

// Now instantiate the Auth0 class with our configuration:
$auth0 = new \Auth0\SDK\Auth0([
  'domain' => $AUTH0_DOMAIN,
  'clientId' => $AUTH0_CLIENT_ID,
  'clientSecret' => $AUTH0_CLIENT_SECRET,
  'cookieSecret' => $AUTH0_COOKIE_SECRET
]);

// Have the SDK complete the authentication flow:
$auth0->exchange($AUTH0_BASE_URL.'/callback');

// Finally, redirect our end user back to the / index route, to display their user profile:
header("Location: " . $AUTH0_BASE_URL);
exit;