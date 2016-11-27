<?php
namespace EtherHttp\Async;

interface FutureInterface
{
    public function get();

    public function wait($seconds);

    public function isDone();
}
