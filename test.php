<?php
namespace EtherHttp\Async;

use \EtherHttp\Async\Swoole\SwooleClient;
use \Zend\Diactoros\Uri;
use \Zend\Diactoros\Request;

require_once 'vendor/autoload.php';

$endpoint = 'http://sensearch.baidu.com/sensearch';

$request = new Request($endpoint, 'GET', 'php://temp', $header = [
    'Accept' => ['text/html,application/xml'],
    'Accept-Encoding' => ['gzip'],
    'User-Agent' => ['Ethercap/1.0'],
    //'Content-Type' =>  ['application/x-www-form-urlencoded']
]);

$instance = new SwooleClient($request);

//override query in $request
$instance->setQuery([
    'from' => 'wise',
    'wd' => 'async'
]);

$instance->send(
    function ($data) {
        echo $data->getBody();
    }
);
