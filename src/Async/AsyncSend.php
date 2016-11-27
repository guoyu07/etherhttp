<?php
namespace EtherHttp\Async;

use EtherHttp\Common\Response;
use React\Promise\Deferred;
use React\Promise\Promise;

/**
 * use AsyncSend requires AsyncClientInterface implemented
 * Created by PhpStorm.
 * User: qieqie
 * Date: 2016/11/21
 */
trait AsyncSend
{
    /**
     * Asynchronously send an HTTP request then execute callback
     * @param callable $callback
     * @param array $options
     */
    public function send(
        callable $callback = null,
        array $options = []
    ) {
        $this->sendAsyncInternal($options)->done(
        //onFulfilled
            function ($data) use (&$callback) {
                if ($callback != null) {
                    $callback($data);
                }
            },
            //onReject
            function ($data) {
                if ($data instanceof \Exception) {
                    echo $data->getMessage() . "\n";
                } elseif ($data instanceof Response) {
                    echo "Error Status: " . $data->getReasonPhrase() . "\n";
                } elseif (is_string($data)) {
                    echo $data . "\n";
                } else {
                    var_export($data);
                }
            }
        );
    }

    /**
     * Asynchronously send an HTTP request
     * @param array $options
     * @return Promise
     */
    public function sendAsyncInternal(array $options = [])
    {
        //$this->setRequest($request);
        $deferred = new Deferred();
        try {
            $this->execute($deferred);
        } catch (\Exception $ex) {
            $deferred->reject($ex);
        }

        return $deferred->promise();
    }
}
