<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 27.09.18
 * Time: 09:04
 */

namespace php_includes;

require_once("APIConnector.php");
require_once("Logging.php");
require_once("ImageCache.php");
require_once("Database.php");

/**
 * Class Updater
 * Handles updates to the database and cache.
 * @package php_includes
 */
class Updater
{

    public static $COMICS_PATH = "comics/";
    public static $VOLUME_INI = "volume.ini";

    /**
     * Update volume and issue information of a directory.
     * @param string $volume_path Path to the volume.
     */
    public static function updateVolume(string $volume_path)
    {
        # Prevent collision with other update or delete functions.
        $fp = fopen("updater.lock", "r+");
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            Logging::printDecoratedString("Starting update for " . $volume_path);
            $volume = self::getDirectoryContent($volume_path);
            self::updateOnDatabase($volume);
            fclose($fp);
            Logging::printDecoratedString("Update done for " . $volume_path);
        }
    }

    /**
     * Update the volume and issue information for all directories.
     */
    public static function updateAll()
    {
        # Prevent collision with other update or delete functions.
        $fp = fopen("updater.lock", "r+");
        if (flock($fp, LOCK_EX | LOCK_NB)) {

            Logging::printDecoratedString("Starting update for whole database...");

            $comics_directories = self::getDirectoryNames();

            $volumes = array();

            foreach ($comics_directories as $comics_directory) {
                array_push($volumes, self::getDirectoryContent(self::$COMICS_PATH . $comics_directory));
            }

            foreach ($volumes as $volume) {
                self::updateOnDatabase($volume);
            }
            fclose($fp);
            Logging::printDecoratedString("Update finished for whole database");
        }
    }

    /**
     * Delete a volume from database and cache.
     * Will not delete the directory.
     * Will delete the volume and issues from the database.
     * Will delete the cached images of the volume and issues.
     * @param string $volume_id ID of the volume.
     */
    public static function deleteVolumeFromLibrary(string $volume_id)
    {
        # Prevent collision with other update or delete functions.
        $fp = fopen("updater.lock", "r+");
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            $volume = Database::readVolume($volume_id);
            $issues = Database::readVolumeIssues($volume_id);
            // Delete images.
            Logging::printDecoratedString("Deleting cached images for " . $volume["name"]);
            ImageCache::deleteImage(rawurlencode(basename($volume["image_medium_url"])));
            foreach ($issues as $issue) {
                ImageCache::deleteImage(rawurlencode(basename($issue["image_medium_url"])));
            }
            // Delete from database.
            Database::deleteVolumeFromDatabase($volume_id);
            fclose($fp);
        }
    }

    /**
     * Delete all volumes from database and cache.
     * Will not delete the directories.
     * Will delete the volumes and issues from the database.
     * Will delete all cached images.
     */
    public static function deleteAllFromLibrary()
    {
        # Prevent collision with other update or delete functions.
        $fp = fopen("updater.lock", "r+");
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            // Delete images.
            Logging::printDecoratedString("Deleting all cached images");
            ImageCache::deleteWholeCache();
            // Delete from database.
            Database::deleteAllFromDatabase();
            fclose($fp);
        }
    }

    /**
     * Update a volume on the database.
     * @param $volume Array with information on the volume.
     */
    public static function updateOnDatabase($volume)
    {
        if (is_array($volume)) {
            # Cache volume from comicvine.
            $local_volume = APIConnector::cacheVolume($volume["volume_id"], $volume["local_path"]);
            foreach ($volume["issues"] as $issue) {
                $issue_data = json_decode($local_volume["issues"], true);
                # Match comic issues to issues from comicvine.
                foreach ($issue_data as $issue_datum) {
                    if ($issue_datum["issue_number"] == $issue["issue_number"]) {
                        APIConnector::cacheIssue($issue_datum["id"], $issue["local_path"]);
                    }
                }
            }
        }
    }

    /**
     * Get a list of directories under the COMICS_PATH, without '.' and '..'.
     * @return array List of the directories.
     */
    public static function getDirectoryNames()
    {
        // Get directories names.
        return array_diff(scandir(self::$COMICS_PATH), array('.', '..'));
    }

    /**
     * Get content of a directory.
     * @param string $directory_path Path to the directory.
     * @return array|null List of content, if any.
     */
    public static function getDirectoryContent(string $directory_path)
    {
        $ini_path = $directory_path . "/" . self::$VOLUME_INI;
        if (is_file($ini_path)) {
            // Get volume info.
            $content = parse_ini_file($ini_path, TRUE);
            $volume = array();
            $volume["volume_id"] = $content["Volume"]["ID"];
            $volume["local_path"] = $directory_path;

            // Get issues info.
            $issues = array();
            $issue = array();
            $comics_files = array_diff(scandir($directory_path), array('.', '..', self::$VOLUME_INI));
            foreach ($comics_files as $comic_file) {
                $file_basename = implode(array_slice(explode('.', $comic_file), 0, -1));
                $issue_number = intval(array_pop(explode('#', $file_basename)));
                $issue["issue_number"] = $issue_number;
                $issue["local_path"] = $directory_path . "/" . $comic_file;
                array_push($issues, $issue);
            }
            $volume["issues"] = $issues;
            return $volume;
        }
    }
}