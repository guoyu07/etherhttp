<?php

$http = new swoole_http_server("127.0.0.1", 9821);
$http->on('request', function ($request, $response) {
    $response->end(json_encode($request));
});
$http->start();
