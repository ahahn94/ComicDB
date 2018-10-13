<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 12.10.18
 * Time: 22:11
 */

namespace php_includes;

require_once("APIConnector.php");

/**
 * Class ImageCache
 * Handle image caching.
 * @package php_includes
 */
class ImageCache
{

    static $cache_path = "cache/images/";

    /**
     * Download an image to the cache.
     * @param string $file_url URL to the image.
     */
    public static function cacheImage(string $file_url)
    {
        $image_file_path = "cache/images/" . rawurldecode(basename($file_url));
        if (!file_exists($image_file_path)) {
            $image = APIConnector::api_call($file_url);
            $image_file = fopen($image_file_path, 'w+');
            fwrite($image_file, $image);
            fclose($image_file);
        }
    }

    /**
     * Delete an image from the cache.
     * @param string $filename Filename of the image.
     */
    public static function deleteImage(string $filename)
    {
        $image_file_path = self::$cache_path . $filename;
        if (file_exists($image_file_path)) {
            unlink($image_file_path);
        }
    }

    /**
     * Clear the whole image cache.
     */
    public static function deleteWholeCache()
    {
        $images = array_diff(scandir(self::$cache_path), array('.', '..', '.keep'));
        foreach ($images as $image) {
            unlink(self::$cache_path . $image);
        }
    }

}