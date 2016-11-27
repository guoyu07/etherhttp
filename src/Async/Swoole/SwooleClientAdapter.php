<?php
namespace EtherHttp\Async\Swoole;

use EtherHttp\Async\AsyncClientInterface;
use EtherHttp\Common\DNSCache as DNS;
use EtherHttp\Common\Response;
use React\Promise\PromisorInterface;

/**
 * Created by PhpStorm.
 * User: qieqie
 * Date: 2016/11/25
 */
class SwooleClientAdapter implements AsyncClientInterface
{
    protected $client;
    protected $method = 'GET';
    protected $path = '/';
    protected $query;
    protected $headers = [];

    public function setUri($uri)
    {
        $port = $uri->getPort();
        if ($uri->getScheme() == 'https') {
            $ssl = true;
            $port = empty($port) ? 443 : $port;
        } else {
            $ssl = false;
            $port = empty($port) ? 80 : $port;
        }
        $ip = DNS::lookup($uri->getHost());
        $this->headers['Host'] = [$uri->getHost()];
        $this->client = new \swoole_http_client($ip, $port, $ssl);
        $this->setPath($uri->getPath());
        $this->setQuery($uri->getQuery());
        return $this;
    }

    public function setMethod($method)
    {
        $this->client->setMethod($method);
        return $this;
    }

    public function setPath($path)
    {
        if(!empty($path)){
            $this->path = (string)$path;
        }
        return $this;
    }

    public function setQuery($query)
    {
        if (is_array($query)) {
            $query = http_build_query($query);
        }
        $this->query = (string)$query;
        return $this;
    }

    public function setHeaders($headers)
    {
        $data = [];
        //[1, 3] + [2, 4, 6] => [1, 3, 6]
        $this->headers = $headers + $this->headers;
        foreach ($this->headers as $name => $arr) {
            if (is_array($arr)) {
                $data[$name] = implode(',', $arr);
            } else {
                $data[$name] = trim($arr);
            }
        }
        $this->client->setHeaders($data);
        return $this;
    }

    public function setBody($data)
    {
        if (is_array($data)) {
            $this->client->setData($data);
        } else {
            $this->client->setData((string)$data);
        }
        return $this;
    }

    public function execute($deferred)
    {
        if (!($deferred instanceof PromisorInterface)) {
            throw new \InvalidArgumentException("execute requires promisor");
        }

        $callback = function ($data) use (&$deferred) {
            //swoole to psr headers
            array_walk($data->headers, function (&$v) {
                $v = explode(',', $v);
            });
            $response = new Response(
                $data->body,
                $data->statusCode,
                $data->headers
            );
            if ($data->statusCode < 400) {
                $deferred->resolve($response);
            } else {
                $deferred->reject("Error status:" . $data->statusCode);
            }
        };

        $this->client->execute(
            $this->query ?
                $this->path . '?' . $this->query : $this->path,
            $callback
        );
    }


    public function close()
    {
        $this->client->close();
    }
}
