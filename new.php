<?php
/*
 * Add new twitter account in here
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
                <div class="col-lg-12">
                    <div class="well bs-component">
                        <form class="form-horizontal">
                            <fieldset>
                                <legend>Add New Hob</legend>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-lg-2 control-label">Twitter User:</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="tuser" placeholder="Twitter User">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-lg-2 control-label">Location</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="location" placeholder="Location">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="textArea" class="col-lg-2 control-label">Description</label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" rows="3" id="decsription"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">

                                        <button type="submit" class="btn btn-primary" onclick="add_hob()">Submit</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12">
                <h3>Hobs</h3>
                <?php hobs(); ?>
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
