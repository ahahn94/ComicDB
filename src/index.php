<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 29.07.18
 * Time: 22:47
 */

/*
 * Main view of the app.
 * Shows the volumes that are on the database.
 */

require_once("php_includes/Database.php");
require_once("php_includes/APIConstants.php");
$volumes = \php_includes\Database::readVolumes();

# Refresh every 10 seconds if database is updating.
$fp = fopen("updater.lock", "r+");
if (!flock($fp, LOCK_EX | LOCK_NB)) {
    header("Refresh: 10");
    fclose($fp);
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    require_once("res/bootstrap.html");
    ?>
    <title>ComicDB</title>
    <!-- Font Awesome Stylesheet -->
    <link rel="stylesheet" href="/res/lib/fontawesome-free-5.5.0-web/css/all.css">
</head>
<body>

<!--Menu-->
<header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid d-flex justify-content-between">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <strong>ComicDB</strong>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
    <div class="collapse bg-dark" id="navbarHeader">

        <div class="container-fluid d-flex justify-content-center">
            <div class="p-2 mr-auto">
                <!--Spacer-->
            </div>
            <div class="p-2 mr-auto">
                <a href="updater_view.php"><h6 class="text-white"><i class="fas fa-sync"></i> Refresh Database</h6></a>
            </div>
            <div class="p-2 mr-auto">
                <!--Spacer-->
            </div>
            <div class="p-2 mr-auto">
                <a data-toggle="collapse" href="#deleteHeader" aria-expanded="false"><h6
                            class="text-white"><i class="fas fa-trash-alt"></i> Delete Database</h6></a>
            </div>
        </div>
        <div class="collapse bg-link" id="deleteHeader">
            <div class="container-fluid d-flex justify-content-center">
                <div class="p-2 mr-auto">
                    <!--Spacer-->
                </div>
                <div class="p-2 mr-auto">

                    <form action="delete_view.php" method="post">
                        <label class="text-white">Do you realy want to delete the whole database?
                            <input type="checkbox" name="confirmation">
                        </label>
                        <button type="submit" class="btn btn-sm btn-dark"><i
                                    class="fas fa-trash-alt" title="Delete"> Delete</i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</header>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="/res/lib/jquery-3.3.1/jquery-3.3.1.min.js"></script>
<script src="/res/lib/popper-1.14.6/popper.min.js"></script>
<script src="/res/lib/bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>

<?php
/*
 * Information message if database is empty.
 */
if ($volumes == null) {
    ?>
    <link href='res/vertical-center.css' rel='stylesheet'>
    <div class="jumbotron vertical-center">
        <div class="lead px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
            <h1 class="display-4">
                Welcome to ComicDB!
            </h1>
            <h2>To initialize the library, please click on "Refresh Library" in the "hamburger" menu.</h2>
            <?php

            if (!\php_includes\APIConstants::apikeyOK()) {
                ?>
                <h3>Please have a look at your api_key.ini and the log first, your API key is not ready for use.</h3>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
} else {
?>

<!--Album view of the volumes-->
<main role="main">

    <div class="album py-5 bg-light">
        <div class="container-fluid">

            <div class="row">

                <?php

                foreach ($volumes as $volume) {
                    echo "<br><br>";
                    ?>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-2">
                        <div class="card mb-4 shadow-sm">
                            <a href="<?php echo "volume_view.php?volume_id=" . $volume["volume_id"]; ?>"><img
                                        class="card-img-top"
                                        src="<?php echo "cache/images/" . rawurldecode(basename($volume["image_medium_url"])); ?>"
                                        alt="Card image cap"></a>
                            <div class="card-body">
                                <p class="card-text"><?php echo $volume["name"]; ?></p>
                                <a href="<?php echo "volume_view.php?volume_id=" . $volume["volume_id"]; ?>"
                                   role="button"
                                   class="btn btn-sm btn-primary my-2">Show Issues</a>
                            </div>
                        </div>
                    </div>

                <?php }
                } ?>
            </div>
        </div>
    </div>

</main>
</body>
</html>
