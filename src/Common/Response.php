<?php
namespace EtherHttp\Common;

use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response as ZendResponse;
use Zend\Diactoros\Stream;

class Response extends ZendResponse
{
    /**
     * Create an general response.
     *
     * @param string|StreamInterface $data HTML or stream for the message body.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @throws \InvalidArgumentException if $html is neither a string or stream.
     */
    public function __construct($data, $status = 200, array $headers = [])
    {
        parent::__construct(
            $this->createBody($data),
            $status,
            $headers
        );
    }

    /**
     * Create the message body.
     *
     * @param string|StreamInterface $data
     * @return StreamInterface
     * @throws \InvalidArgumentException if $html is neither a string or stream.
     */
    private function createBody($data)
    {
        if ($data instanceof StreamInterface) {
            return $data;
        }

        if (! is_string($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid content (%s) provided to %s',
                (is_object($data) ? get_class($data) : gettype($data)),
                __CLASS__
            ));
        }

        $body = new Stream('php://temp', 'wb+');
        $body->write($data);
        $body->rewind();
        return $body;
    }
}
