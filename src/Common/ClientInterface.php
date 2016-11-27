<?php
namespace EtherHttp\Common;

/**
 * Created by PhpStorm.
 * User: qieqie
 * Date: 2016/11/23
 */
interface ClientInterface
{
    public function setMethod($method);

    public function setUri($uri);

    public function setHeaders($headers);

    public function setBody($data);

}
