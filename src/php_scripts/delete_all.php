<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 12.10.18
 * Time: 22:21
 */

/*
 * Trigger background deletion of all volumes and issues.
 */
require_once("php_includes/Updater.php");
\php_includes\Updater::deleteAllFromLibrary();