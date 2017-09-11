<?php 
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/twitteroauth/autoload.php';

session_start();
//session_destroy();
// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Register middleware
require __DIR__ . '/../src/funcs.php';

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';


if (!isset($_SESSION['access_token'])) {
    
        if ( 0 == filesize( 'tmp.bin' ) ){
            $connection = new \Abraham\TwitterOAuth\TwitterOAuth(
                $container->get('settings')['twitter']['consumer_key'],
                $container->get('settings')['twitter']['consumer_secret']
            );
            $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $container->get('settings')['twitter']['oauth_callback']));
            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
            //file_put_contents('tmp.bin', serialize($request_token));
            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
            header("Location: $url");
            $app->stop();
        }else{
            $data = unserialize(file_get_contents('tmp.bin'));
            //var_dump($data);
            if(isset($data['oauth_token']) && isset($data['oauth_token_secret'])){
                $_SESSION = $data;
            }
        }
}else{
    if ( 0 == filesize( 'tmp.bin' ) ){
        //var_dump($_SESSION);
        file_put_contents('tmp.bin', serialize($_SESSION));
    }
}

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

// Run app
$app->run();
