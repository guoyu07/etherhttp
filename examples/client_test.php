<?php
namespace EtherHttp\Async;

use \EtherHttp\Async\Swoole\SwooleClient;
use \Zend\Diactoros\Request;

require_once __DIR__.'/../vendor/autoload.php';

//$endpoint = 'http://sensearch.baidu.com/sensearch?wd=test';
$endpoint = 'http://127.0.0.1:9821';
$request = new Request($endpoint, 'POST', 'php://temp', $header = [
    'Accept' => ['text/html','application/xml'],
    'Accept-Encoding' => ['gzip'],
    'User-Agent' => ['Ethercap/1.0'],
    'Content-Type' =>  ['application/x-www-form-urlencoded']
]);

$instance = new SwooleClient($request);
//override
$instance->setBody(http_build_query([
    'test'=>123,
    'echo'=>'aaa',
]));

$instance->send(function($data){
    echo $data->getBody();
});
