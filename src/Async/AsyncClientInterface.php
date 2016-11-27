<?php
namespace EtherHttp\Async;

use EtherHttp\Common\ClientInterface;
use React\Promise\Deferred;

/**
 * Created by PhpStorm.
 * User: qieqie
 * Date: 2016/11/24
 */
interface AsyncClientInterface extends ClientInterface
{
    public function execute($promisorOrCallback);
}
