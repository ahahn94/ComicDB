<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 27.09.18
 * Time: 10:56
 */

/*
 * Trigger background update for all directories.
 */
require_once("php_includes/Updater.php"); // Called from the webroot, so no '..'.
\php_includes\Updater::updateAll();