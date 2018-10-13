<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 18.09.18
 * Time: 22:38
 */

/*
 * View that triggers updates.
 * Shows an info message and redirects to index.php.
 */

require_once("php_includes/Updater.php");

if (isset($_GET["volume_path"])) {
    exec("php php_scripts/update_volume.php \"" . $_GET["volume_path"] . "\" >> log.txt &");
} else {
    exec("php php_scripts/update_all.php >> log.txt &");
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
    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
</head>

<?php
require_once("res/header.html");
?>
<link href="res/vertical-center.css" rel="stylesheet">
<div class="jumbotron vertical-center">
    <div class="lead px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">

            <?php
            if (isset($_GET["volume_path"])) {
                print("Update for \"" . $_GET["volume_path"] . "\" started!<br>Redirecting to start page...");
            } else {
                print("Update for whole library started!<br>Redirecting to start page...");
            }
            ?>
        </h1>
    </div>
</div>