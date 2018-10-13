<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 25.08.18
 * Time: 12:15
 */

namespace php_includes;

use PDO;

/**
 * Class Connection
 * Handles the connection to the database.
 * @package php_includes
 */
class Connection
{
    // Database config.
    private static $DB_SERVER_IP = "database";
    private static $DB_USER_NAME = "ComicDB";
    private static $DB_USER_PASSWORD = "keinsicherespasswort";
    private static $DB_NAME = "ComicDB";

    private static $instance = NULL;

    /**
     * Connection constructor.
     */
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * Get the connection. Initialization on first use.
     * @return null|PDO
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "Set Names utf8";
            self::$instance = new PDO("mysql:host=" . self::$DB_SERVER_IP . ";dbname=" . self::$DB_NAME, self::$DB_USER_NAME, self::$DB_USER_PASSWORD, $pdo_options);
            self::checkInitialization();
        }
        return self::$instance;
    }

    /**
     * Initialize the database tables if necessary.
     */
    private static function checkInitialization()
    {
        $connection = self::getInstance();
        $statement = $connection->prepare("SHOW TABLES FROM ComicDB;");
        $statement->execute();
        if ($statement->rowCount() != 0) {
            $tables = $statement->fetchAll(PDO::FETCH_ASSOC);
            $tables = array_column($tables, "Tables_in_ComicDB");
            if (!(in_array("Issues", $tables) and in_array("Volumes", $tables))) {
                self::initDatabase();
            }
        } else {
            self::initDatabase();
        }
    }

    /**
     * Initialize the database tables.
     */
    private static function initDatabase()
    {
        $connection = self::getInstance();
        $connection->exec("
CREATE TABLE Volumes (
  volume_id        VARCHAR(100) PRIMARY KEY,
  local_path       TEXT,
  api_detail_url   TEXT,
  image_medium_url TEXT,
  name             TEXT,
  description      TEXT,
  publisher_id     VARCHAR(100),
  publisher_name   TEXT,
  start_year       INTEGER,
  issues           TEXT
);

CREATE TABLE Issues (
  issue_id         VARCHAR(100) PRIMARY KEY,
  local_path       TEXT,
  api_detail_url   TEXT,
  image_medium_url TEXT,
  issue_number     INTEGER,
  volume_id        VARCHAR(100),
  read_status      BOOL,
  FOREIGN KEY (volume_id) REFERENCES Volumes (volume_id)
);");
    }

}