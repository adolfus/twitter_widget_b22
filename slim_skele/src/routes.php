<?php
// Routes
    
$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/user/{screen_name}/timeline', function($request, $response, $args) use ($app){
    $container = $app->getContainer();
    $access_token = $_SESSION['access_token'];
    $connection = new \Abraham\TwitterOAuth\TwitterOAuth(
        $container->get('settings')['twitter']['consumer_key'],
        $container->get('settings')['twitter']['consumer_secret'],
        $access_token['oauth_token'],
        $access_token['oauth_token_secret']
    );
    $tweets = $connection->get('statuses/home_timeline', ['count' => 20, 'exclude_replies' => true, 'screen_name' => $args['screen_name'], 'include_rts' => false]);
    
    $new_tweets = array();
    $data = array('created_at' => 0, 'id' => 0, 'text' => 0, 'user' => 0);
    $data_user = array('id' => 0,'name' => 0, 'screen_name' => 0, 'profile_image_url' => 0);
    foreach($tweets as $tweet){
        
        $data = array_intersect_key((array)$tweet, $data);        
        $data['user'] = array_intersect_key((array)$data['user'], $data_user);
        $data['user']['screen_name'] = '@'.$data['user']['screen_name'];
        $new_tweets[] = $data;
    }
    
    return echoResponse(200,$new_tweets, $response);
});


$app->post('/tweet', function($request, $response) use ($app){
    $container = $app->getContainer();
    $data = unserialize(file_get_contents('tmp.bin'));
    $access_token = $data['access_token'];
    $connection = new \Abraham\TwitterOAuth\TwitterOAuth(
        $container->get('settings')['twitter']['consumer_key'],
        $container->get('settings')['twitter']['consumer_secret'],
        $access_token['oauth_token'],
        $access_token['oauth_token_secret']
    );
    $input = $request->getParsedBody();

    $ret = array(); $param = array();
    
    $post = $connection->post('statuses/update', array('status' => $input['twitter-text']));

    if ($connection->getLastHttpCode() == 200) {
        $ret["error"] = false;
        $ret["message"] = "twitt enviado satisfactoriamente!";
        $ret["twitt"] = $input['twitter-text'];
    } else {
        $ret["error"] = true;
        $ret["errort"] = $post;
        $ret["message"] = "Error al enviar tweet. Por favor intenta nuevamente.";
    }
    return echoResponse(201,$ret, $response);
});