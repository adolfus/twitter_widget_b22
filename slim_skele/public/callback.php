<?php
session_start();
require __DIR__ . '/../vendor/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define("CONSUMER_KEY", "GvHS0rNxfTRRChti5MGsIWlOA");
define("CONSUMER_SECRET", "iHz6wDlwVMKKcgDOF9fGYseZS4qYXBU931B4UfMDN2cWh3nsQD");
print_r($_SESSION);
if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
    $_SESSION['access_token'] = $access_token;
    // redirect user back to index page
    var_dump($access_token);
    //header('Location: http://localhost:8083/twitter_base22');
}