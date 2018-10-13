<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 25.08.18
 * Time: 12:24
 */

namespace php_includes;

use PDO;

require_once("Connection.php");
require_once("Logging.php");

/**
 * Class Database
 * Handles the CRUD operations on the database.
 * @package php_includes
 */
class Database
{

    /**
     * Read an issue from the database.
     * @param string $issue_id ID of the issue.
     * @return mixed|null
     */
    public static function readIssue(string $issue_id)
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare("SELECT * FROM Issues WHERE issue_id = ?;");
        $statement->execute(array($issue_id));
        if ($statement->rowCount() != 0) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    /**
     * Read a volume from the database.
     * @param string $volume_id ID of the volume.
     * @return mixed|null
     */
    public static function readVolume(string $volume_id)
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare("SELECT * FROM Volumes WHERE volume_id = ?;");
        $statement->execute(array($volume_id));
        if ($statement->rowCount() != 0) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    /**
     * Read all volumes from the database.
     * @return array|null
     */
    public static function readVolumes()
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare("SELECT * FROM Volumes ORDER BY name;");
        $statement->execute();
        if ($statement->rowCount() != 0) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    /**
     * Read all issues of a volume from the database.
     * @param string $volume_id ID of the volume.
     * @return array|null
     */
    public static function readVolumeIssues(string $volume_id)
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare("SELECT * FROM Issues WHERE volume_id = ? ORDER BY issue_number;");
        $statement->execute(array($volume_id));
        if ($statement->rowCount() != 0) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    /**
     * Update the read_status of an issue on the database.
     * @param string $issue_id ID of the issue.
     * @param string $read_status New read_status.
     * @return string Error code if any.
     */
    public static function updateReadStatus(string $issue_id, string $read_status)
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare(
            "UPDATE Issues SET read_status = :read_status WHERE issue_id = :issue_id");
        $statement->execute(array("issue_id" => $issue_id, "read_status" => $read_status));
        return $statement->errorCode();
    }

    /**
     * Write an issue to the database.
     * Update if already on database.
     * @param array $data Data of the issue.
     * @return string Error code if any.
     */
    public static function writeIssue(array $data)
    {
        $issue = self::readIssue($data["issue_id"]);
        $connection = Connection::getInstance();
        if ($issue == null) {
            $statement = $connection->prepare(
                "INSERT INTO Issues (issue_id, local_path, api_detail_url, image_medium_url, issue_number, volume_id, read_status) VALUES (:issue_id, :local_path, :api_detail_url, :image_medium_url, :issue_number, :volume_id, FALSE);");
            Logging::printDecoratedString("Creating issue dataset for " . $data["local_path"]);
        } else {
            $statement = $connection->prepare(
                "UPDATE Issues SET local_path = :local_path, api_detail_url = :api_detail_url, image_medium_url = :image_medium_url, issue_number = :issue_number, volume_id = :volume_id WHERE issue_id = :issue_id"
            );
            Logging::printDecoratedString("Updating issue dataset for " . $data["local_path"]);
        }
        $statement->execute($data);
        return $statement->errorCode();
    }

    /**
     * Write a volume to the database.
     * Update if already on database.
     * @param array $data Data of the volume.
     * @return string Error code if any.
     */
    public static function writeVolume(array $data)
    {
        $volume = self::readVolume($data["volume_id"]);
        $connection = Connection::getInstance();
        if ($volume == null) {
            $statement = $connection->prepare(
                "INSERT INTO Volumes (volume_id, local_path, api_detail_url, image_medium_url, name, description, publisher_id, publisher_name, start_year, issues) VALUES (:volume_id, :local_path, :api_detail_url, :image_medium_url, :name, :description, :publisher_id, :publisher_name, :start_year, :issues);"
            );
            Logging::printDecoratedString("Creating volume dataset for " . $data["local_path"]);
        } else {
            $statement = $connection->prepare(
                "UPDATE Volumes SET local_path = :local_path, api_detail_url = :api_detail_url, image_medium_url = :image_medium_url, name = :name, description = :description, publisher_id = :publisher_id, publisher_name = :publisher_name, start_year = :start_year, issues = :issues WHERE volume_id = :volume_id"
            );
            Logging::printDecoratedString("Updating issue dataset for " . $data["local_path"]);
        }
        $statement->execute($data);
        return $statement->errorCode();
    }

    /**
     * Delete a volume and its issues from the database.
     * @param string $volume_id ID of the volume.
     * @return string Error code if any.
     */
    public static function deleteVolumeFromDatabase(string $volume_id)
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare(
            "DELETE FROM Issues WHERE volume_id = :volume_id; DELETE FROM Volumes WHERE volume_id = :volume_id");
        Logging::printDecoratedString("Deleting volume with volume_id $volume_id and its issues from database.");
        $statement->execute(array("volume_id" => $volume_id));
        return $statement->errorCode();
    }

    /**
     * Delete all volumes and issues from the database.
     * @return string Error code if any.
     */
    public static function deleteAllFromDatabase()
    {
        $connection = Connection::getInstance();
        $statement = $connection->prepare(
            "DELETE FROM Issues; DELETE FROM Volumes");
        Logging::printDecoratedString("Deleting all volumes and issues from database!");
        $statement->execute(array());
        return $statement->errorCode();
    }

}
