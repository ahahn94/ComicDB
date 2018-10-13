<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 18.09.18
 * Time: 22:38
 */

/*
 * View that triggers deletions.
 * Shows an info message and redirects to index.php.
 */

require_once("php_includes/Updater.php");

if ($_POST["confirmation"] == "on") {
    if (isset($_POST["volume_id"])) {
        exec("php php_scripts/delete_volume.php \"" . $_POST["volume_id"] . "\" >> log.txt &");
    } else {
        exec("php php_scripts/delete_all.php >> log.txt &");
    }
}


header('Refresh: 5; URL=' . 'index.php');
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    require_once("res/bootstrap.html");
    ?>
    <title>ComicDB</title>
</head>

<?php
require_once("res/header.html");
?>
<link href="res/vertical-center.css" rel="stylesheet">
<div class="jumbotron vertical-center">
    <div class="lead px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">

            <?php
            if ($_POST["confirmation"] == "on") {
                if (isset($_POST["volume_path"])) {
                    print("Deletion of \"" . $_POST["volume_path"] . "\" from database started!<br>Redirecting to start page...");
                } else {
                    print("Deletion of the whole library started!<br>Redirecting to start page...");
                }
            } else {
                print("Missing confirmation. No deletions.<br>Redirecting to start page...");
            }
            ?>
        </h1>
    </div>
</div>