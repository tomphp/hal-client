<?php

$endpointsDb = __DIR__ . '/endpoints.db';

/** @return array */
function getEndpoints()
{
    global $endpointsDb;

    $endpoints = [];

    if (file_exists($endpointsDb)) {
        $endpoints = unserialize(file_get_contents($endpointsDb));
    }

    return $endpoints;
}

function throw404()
{
    header('HTTP/1.1 404 Not Found');
    exit();
}

function run()
{
    $path   = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    $endpoints = getEndpoints();

    if (!array_key_exists($method, $endpoints)) {
        throw404();
    }

    if (!array_key_exists($path, $endpoints[$method])) {
        throw404();
    }

    $endpoint = $endpoints[$method][$path];

    header('content-type: ' . $endpoint['contentType']);

    echo $endpoint['body'];
}

run();
