<?php
/**
 * Scheme default ports
 * User: moyo
 * Date: 25/08/2017
 * Time: 5:42 PM
 */

namespace Carno\HTTP\Standard\Utils;

class SchemePorts
{
    /**
     * scheme2ports map
     */
    private const S2PS = [
        'http'  => 80,
        'https' => 443,
        'ws' => 80,
        'wss' => 443,
        'ftp' => 21,
        'gopher' => 70,
        'nntp' => 119,
        'news' => 119,
        'telnet' => 23,
        'imap' => 143,
        'pop' => 110,
        'ldap' => 389,
    ];

    /**
     * @param string $scheme
     * @param int $port
     * @return bool
     */
    public static function defined(string $scheme, int $port) : bool
    {
        return (self::S2PS[$scheme] ?? null) === $port;
    }

    /**
     * @param string $scheme
     * @return int|null
     */
    public static function resolve(string $scheme) : ?int
    {
        return self::S2PS[$scheme] ?? null;
    }
}
