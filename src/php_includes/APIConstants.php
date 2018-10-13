<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 05.10.18
 * Time: 14:19
 */

namespace php_includes;
require_once("Logging.php");


/**
 * Class APIConstants
 * Contains the constants for the usage of the Comicvine API.
 * @package php_includes
 */
class APIConstants
{
    private static $CONSTANTS = NULL;

    /**
     * APIConstants constructor.
     */
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * Get the constants. Initialization on first use.
     * @return array|null
     */
    public static function getConstants()
    {
        if (!isset(self::$CONSTANTS)) {
            self::$CONSTANTS = array(
                "issue" => "4000-",
                "volume" => "4050-",
            );
            self::$CONSTANTS["api_key"] = "api_key=" . self::importApiKey();
        }
        return self::$CONSTANTS;
    }

    /**
     * Import the API key from the api-key.ini.
     * @return null
     */
    private static function importApiKey()
    {
        $ini_path = realpath("./api-key.ini"); // Called from webroot, so only filename is needed.
        if (is_file($ini_path)) {
            Logging::printDecoratedString("api-key.ini found!");
            // Get api key.
            $content = parse_ini_file($ini_path, TRUE);
            $api_key = $content["API"]["key"];
            return $api_key;
        }
        Logging::printDecoratedString("Could not import API key!");
        return null;
    }
}
