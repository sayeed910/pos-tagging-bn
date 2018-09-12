<?php

require_once '../vendor/autoload.php';
$tags = require_once '../src/tags.php';

use App\Registry;



$get_routes = [
    'pos-tagging' => 'loadPosTaggingPage',
    'api/sentences' => 'fetchSentences',
    '' => 'loadFrontPage', //needed to be last because empty regex matches everything
];

$post_routes = [
    'api/sentences' => 'saveTags'
];

$request_uri = $_SERVER['REQUEST_URI'];
$request_type = $_SERVER['REQUEST_METHOD'];


if ($request_type == 'GET'){
    dispatch_request($get_routes, $request_uri, $_GET);
} else if ($request_type == 'POST') {
    dispatch_request($post_routes, $request_uri, $_POST);
}

function dispatch_request($routes, $uri, $params){
    foreach($routes as $key => $value){
        $matches = [];
        $pattern = "@".$key."$@";
        $result = preg_match($pattern, $uri, $matches);

        if ($result == 1){
            $value($matches, $params);
            return;
        }

    }
}

function loadFrontPage(){
    require_once './front.html';
}

function loadPosTaggingPage(){
    require_once './pos-tagging.html';
}

function fetchSentences($params){
    /** @var \App\SentenceService $service */
    $service = Registry::getInstance(\App\SentenceService::class);
    $count = 10;
    if (array_key_exists('count', $params)){
        $count = $params['count'];
    }
    $sentences = $service->getRandomSentences($count);


    $response = ['sentence_count' => count($sentences), 'sentences' => []];

    foreach ($sentences as $sentence){
        $response['sentences'][$sentence->getSentenceId()] = $sentence->getSentence();
    }

    global $tags;

    $response['tag_count'] = count($tags);
    $response['tags'] = $tags;

    echo json_encode($response);
}

function saveTags($urlExtracts, $params){
    $data = [];
    $data['params'] = $params;

    echo json_encode($data);
}
