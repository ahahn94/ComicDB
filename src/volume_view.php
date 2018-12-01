<?php
/**
 * Created by PhpStorm.
 * User: ahahn94
 * Date: 29.07.18
 * Time: 22:47
 */

/*
 * View for the issues of an volume.
 */

require_once("php_includes/Database.php");

$volume_id = $_GET["volume_id"];

$this_site = "volume_view.php?volume_id=$volume_id";

if (isset($_POST["new_read_status"])) {
    \php_includes\Database::updateReadStatus($_POST["issue_id"], $_POST["new_read_status"]);
}

$volume = \php_includes\Database::readVolume($volume_id);

$issues = \php_includes\Database::readVolumeIssues($volume_id);

?>

<!doctype html>
<html lang="en">
<head>
    <?php
    require_once("res/bootstrap.html");
    ?>
    <title><?php echo $volume["name"]; ?></title>

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
                <a href="updater_view.php?volume_path=<?php print(urlencode($volume["local_path"])) ?>"><h6
                            class="text-white"><i class="fas fa-sync"></i> Refresh Volume</h6></a>
            </div>
            <div class="p-2 mr-auto">
                <a data-toggle="collapse" href="#deleteHeader" aria-expanded="false"><h6
                            class="text-white"><i class="fas fa-trash-alt"></i> Delete Volume</h6></a>
            </div>
        </div>
        <div class="collapse bg-link" id="deleteHeader">
            <div class="container-fluid d-flex justify-content-center">
                <div class="p-2 mr-auto">
                    <!--Spacer-->

                </div>
                <div class="p-2 mr-auto">

                    <form action="delete_view.php" method="post">
                        <input type="hidden" name="volume_id" value="<?php echo $volume_id ?>">
                        <input type="hidden" name="volume_path" value="<?php echo $volume["local_path"]; ?>">
                        <label class="text-white">Do you realy want to delete this volume?
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

<style> .jumbotron {
        position: relative;
    }

    ;</style>
<style>.back-button {
        position: absolute;
        top: 5px;
        left: 5px;
    }</style>

<main role="main">
    <!--Page Head-->
    <section class="jumbotron">
        <div class="back-button">
            <a class="btn btn-md text-dark" href="index.php"><i class="fa fa-arrow-circle-left"></i><strong>
                    Library</strong></a>
        </div>
        <div class="container-fluid">
            <div class="text-center">
                <h1><a href="<?php echo $this_site; ?>" style="text-decoration: none"
                       class="text-dark"><?php echo $volume["name"]; ?></a></h1>
                <h4 class="text-muted">Show Description
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#description"
                            aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"
                            title="Show Description">
                        <i class="fas fa-caret-square-right"></i>
                    </button>
                </h4>
            </div>
        </div>
    </section>
    <div class="row">
        <div class=" collapse col-xs-12 col-md-3 px-5" id="description">
            <h4 class="text-muted">Publisher: <?php echo $volume["publisher_name"] ?></h4>
            <h4 class="text-muted">Issues: <?php echo count($issues); ?></h4>
            <h4 class="text-muted">Year: <?php echo $volume["start_year"]; ?></h4>
            <h4 class="text-muted">Description:</h4>
            <?php echo $volume["description"]; ?>
        </div>
        <div class="col px-3">
            <div class="album py-5 bg-light">
                <div class="container-fluid">

                    <div class="row">

                        <?php foreach ($issues as $volume_issue) {
                            $filelink = $volume_issue["local_path"];
                            $filelink = rawurlencode($filelink);
                            $filelink = str_replace('%3A', ':', str_replace('%2F', '/', $filelink));
                            ?>

                            <div class="col-6 col-md-3 col-lg-2 col-xl-2 card-group">
                                <div class="card mb-4 shadow-sm">
                                    <a href="<?php echo "cache/images/" . basename($volume_issue["image_medium_url"]); ?>"><img
                                                class="card-img-top"
                                                src="<?php echo "cache/images/" . basename($volume_issue["image_medium_url"]); ?>"
                                                alt="Card image cap"></a>
                                    <div class="card-body">
                                        <p class="card-text">Issue #<?php echo $volume_issue["issue_number"]; ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a href="<?php echo $filelink; ?>" role="button" type="button"
                                                   class="btn btn-sm btn-primary my-2" download><i
                                                            class="fas fa-download"></i><strong> Download</strong>
                                                </a>
                                                <?php
                                                if ($volume_issue["read_status"] == 0) {
                                                    ?>
                                                    <form action="<?php echo $this_site; ?>"
                                                          method="post">
                                                        <input type="hidden" name="new_read_status" value="1">
                                                        <input type="hidden" name="issue_id"
                                                               value="<?php echo $volume_issue["issue_id"]; ?>">
                                                        <button type="submit" class="btn btn-sm my-2"><i
                                                                    class="fas fa-eye-slash" title="Mark as read"></i>
                                                        </button>
                                                    </form>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <form action="<?php echo $this_site; ?>"
                                                          method="post">
                                                        <input type="hidden" name="new_read_status" value="0">
                                                        <input type="hidden" name="issue_id"
                                                               value="<?php echo $volume_issue["issue_id"]; ?>">
                                                        <button type="submit" class="btn btn-sm my-2"><i
                                                                    class="fas fa-eye" title="Mark as unread"></i>
                                                        </button>
                                                    </form>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <small class="text-muted"><?php echo $volume_issue["issue_number"]; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
