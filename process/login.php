<?php

include('../secrets.php');

// Import the Composer Autoloader to make the SDK classes accessible:
require '../vendor/autoload.php';

// Now instantiate the Auth0 class with our configuration:
$auth0 = new \Auth0\SDK\Auth0([
  'domain' => $AUTH0_DOMAIN,
  'clientId' => $AUTH0_CLIENT_ID,
  'clientSecret' => $AUTH0_CLIENT_SECRET,
  'cookieSecret' => $AUTH0_COOKIE_SECRET
]);

// It's a good idea to reset user sessions each time they go to login to avoid "invalid state" errors, should they hit network issues or other problems that interrupt a previous login process:
$auth0->clear();

// Finally, set up the local application session, and redirect the user to the Auth0 Universal Login Page to authenticate.
header("Location: " . $auth0->login($AUTH0_BASE_URL.'/callback'));
exit;