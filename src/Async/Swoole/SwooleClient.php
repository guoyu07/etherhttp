<?php
namespace EtherHttp\Async\Swoole;

use EtherHttp\Async\AsyncSend;
use Psr\Http\Message\RequestInterface;

/**
 * Created by PhpStorm.
 * User: qieqie
 * Date: 2016/11/24
 */
class SwooleClient extends SwooleClientAdapter
{
    use AsyncSend;

    public function __construct(RequestInterface $request)
    {
        $this->setRequest($request);
    }

    public function setRequest(RequestInterface $request)
    {
        $this->setUri($request->getUri())
            ->setMethod($request->getMethod())
            ->setBody($request->getBody())
            ->setHeaders($request->getHeaders());
    }
}
