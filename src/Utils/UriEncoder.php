<?php
/**
 * Uri parts encoder
 * User: moyo
 * Date: 09/09/2017
 * Time: 2:01 AM
 */

namespace Carno\HTTP\Standard\Utils;

class UriEncoder
{
    private const CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';
    private const CHAR_SUBDELIMS = '!\$&\'\(\)\*\+,;=';

    private const PREG_PATH =
        '/(?:[^' . self::CHAR_UNRESERVED . self::CHAR_SUBDELIMS . '%:@\/]++|%(?![A-Fa-f0-9]{2}))/';

    private const PREG_QUERY =
        '/(?:[^' . self::CHAR_UNRESERVED . self::CHAR_SUBDELIMS . '%:@\/\?]++|%(?![A-Fa-f0-9]{2}))/';

    public const TYPE_PATH = 0x1;
    public const TYPE_QUERY = 0x2;

    /**
     * @param int $type
     * @param string $input
     * @return string
     */
    public static function process(int $type, string $input) : string
    {
        return preg_replace_callback(
            $type === self::TYPE_PATH ? self::PREG_PATH : self::PREG_QUERY,
            static function (array $match) {
                return rawurlencode($match[0]);
            },
            $input
        );
    }
}
