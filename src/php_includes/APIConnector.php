<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 26.09.18
 * Time: 21:45
 */

namespace php_includes;

require_once("APIConstants.php");
require_once("Database.php");
require_once("Logging.php");
require_once("ImageCache.php");

/**
 * Class APIConnector
 * Handle the connection to the Comicvine API.
 * @package php_includes
 */
class APIConnector
{

    /**
     * Make a call to the API.
     * @param string $url URL for API call.
     * @return mixed Result of the call.
     */
    public static function api_call(string $url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
            CURLOPT_USERAGENT => "ComicDB"
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * Download a volume from the API to the local database.
     * Download the volume image to the cache.
     * @param string $volume_id ID of the volume.
     * @param string $local_path Path to the directory containing the volume.
     * @return array Copy of the volume on the database.
     */
    public static function cacheVolume(string $volume_id, string $local_path)
    {
        $constants = APIConstants::getConstants();
        $result = self::api_call("https://comicvine.gamespot.com/api/volume/" . $constants["volume"] . $volume_id . "/?" . $constants["api_key"] . "&format=json");
        $decoded = json_decode($result, true);
        if ($decoded["error"] == "OK") {
            # Fill out volume details
            $volume = array();
            $volume["volume_id"] = $decoded["results"]["id"];
            $volume["local_path"] = $local_path;
            $volume["api_detail_url"] = $decoded["results"]["api_detail_url"];
            $volume["image_medium_url"] = $decoded["results"]["image"]["medium_url"];
            $volume["name"] = $decoded["results"]["name"];
            $volume["description"] = $decoded["results"]["description"];
            $volume["publisher_id"] = $decoded["results"]["publisher"]["id"];
            $volume["publisher_name"] = $decoded["results"]["publisher"]["name"];
            $volume["start_year"] = $decoded["results"]["start_year"];
            $volume["issues"] = json_encode($decoded["results"]["issues"]);

            Database::writeVolume($volume);

            ImageCache::cacheImage($volume["image_medium_url"]);

            return $volume;
        } else {
            Logging::printDecoratedString("Error reading from Comicvine API! ($volume_id, $local_path)");
        }
        return null;
    }

    /**
     * Download an issue from the API to the local database.
     * Download the issue image to the cache.
     * @param string $issue_id ID of the issue.
     * @param string $local_path Path to the issue.
     * @return array Copy of the issue on the database.
     */
    public static function cacheIssue(string $issue_id, string $local_path)
    {
        $constants = APIConstants::getConstants();
        $result = self::api_call("https://comicvine.gamespot.com/api/issue/" . $constants["issue"] . $issue_id . "/?" . $constants["api_key"] . "&format=json");
        $decoded = json_decode($result, true);
        if ($decoded["error"] == "OK") {
            # Fill out volume details
            $issue = array();
            $issue["issue_id"] = $decoded["results"]["id"];
            $issue["local_path"] = $local_path;
            $issue["api_detail_url"] = $decoded["results"]["api_detail_url"];
            $issue["image_medium_url"] = $decoded["results"]["image"]["medium_url"];
            $issue["issue_number"] = $decoded["results"]["issue_number"];
            $issue["volume_id"] = $decoded["results"]["volume"]["id"];

            Database::writeIssue($issue);

            ImageCache::cacheImage($issue["image_medium_url"]);

            return $issue;
        } else {
            Logging::printDecoratedString("Error reading from Comicvine API! ($issue_id, $local_path)");
        }
        return null;
    }

}
