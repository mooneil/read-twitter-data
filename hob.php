<?php
/*
 * In this page We show information of account of twitter like:
 * twitter name , amount of followers and followers
 * and when click on add followers new followers add to Mysql DB
 */
require_once "server/functions.php";
?>
<!DOCTYPE html>
<html >
<head>
    <?php
        require_once "style/head.php";
    ?>

</head>
<body >
<?php require_once "style/mainmenu.php"; ?>
<div class="container">
    <div class="row">
        <div id="message" class="col-lg-12"></div>
    </div>
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h3>Hobs</h3>
                <?php hobs(); ?>
            </div>

        </div>
        <div class="row">
            <h3>Users</h3>
            <div id="results" class="col-lg-12">

            </div>

        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
    <script src="js/ajaxall.js"></script>
</body>
</html>
