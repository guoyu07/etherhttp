<?php
namespace EtherHttp\Common;

class DNSCache
{
    protected static $cache = [];
    private static $ttl = 60 * 60;

    /**
     * @param string $host
     * @return string ip or unresolved host
     */
    public static function lookup($host)
    {
        if (empty($cache[$host])) {
            $resolved = gethostbyname($host);
            if ($resolved === $host) {
                return $host;
            }
            self::$cache[$host] = $resolved;
            /*swoole_async_dns_lookup(
                $host, function($host, $ip){
            });*/
            swoole_timer_after(
                self::$ttl * 1000,
                function () use ($host) {
                    //异步定时失效DNS记录
                    unset(self::$cache[$host]);
                }
            );
        }
        return self::$cache[$host];
    }

    public static function setTTL($seconds)
    {
        if ($seconds > 0) {
            self::$ttl = intval($seconds);
        }
    }
}
