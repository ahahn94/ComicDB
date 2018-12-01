<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 12.10.18
 * Time: 21:47
 */

namespace php_includes;

/**
 * Class Logging
 * Class to simplify logging.
 * @package php_includes
 */
class Logging
{

    /**
     * Decorated output of logging messages.
     * Prepends date and time, appends linebreak.
     * @param string $message Logging message.
     */
    public static function printDecoratedString(string $message)
    {
        file_put_contents("log.txt", date("d.m.Y H:i:s (e) ", time()) . $message . PHP_EOL, FILE_APPEND);
    }
}