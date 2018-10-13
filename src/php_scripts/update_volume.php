<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 27.09.18
 * Time: 10:23
 */

/*
 * Trigger background update of a single directory.
 */
require_once("php_includes/Updater.php"); // Called from webroot, so no '..'.
\php_includes\Updater::updateVolume($argv[1]);