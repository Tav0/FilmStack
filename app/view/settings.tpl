<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<title>
    <?= $title ?>
    <?php
    if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
        echo '&middot; '.$_SESSION['username'];
    }
    ?>
</title>

<!-- Bootstrap -->
<link href="<?= BASE_URL ?>/public/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/public/css/carousel.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/public/css/footer.css" rel="stylesheet">
<!-- settings -->
<link href="<?= BASE_URL ?>/public/css/settings.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body>
    <div class="page-container">
        <!-- nav -->
        <?php
        include_once SYSTEM_PATH.'/view/nav.tpl';
        ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1><?= $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form id="settings-form" class="form-horizontal">
                        <!-- ajax messages displayed in this paragraph -->
                        <div class="ajax-messages"></div>
                    
                        <!-- first name -->
                        <div class="form-group">
                            <label for="inputFirstName" class="col-sm-2 control-label">First name</label>
                            <div class="col-sm-10">
                                <input name="first-name" type="text" class="form-control" id="inputFirstName" placeholder="first name" value="<?= $user['FirstName'] ?>">
                            </div>
                        </div>
                        <!-- last name -->
                        <div class="form-group">
                            <label for="inputLastName" class="col-sm-2 control-label">Last name</label>
                            <div class="col-sm-10">
                                <input name="last-name" type="text" class="form-control" id="inputLastName" placeholder="last name" value="<?= $user['LastName'] ?>">
                            </div>
                        </div>
                        <!-- email -->
                        <div class="form-group">
                            <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input  name="email" type="email" class="form-control" id="inputEmail" placeholder="email" value="<?= $user['Email'] ?>">
                            </div>
                        </div>
                        <!-- password -->
                        <div class="form-group">
                            <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
                            </div>
                        </div>
                        <!-- preference of number of events the user wants to see in their home activity feed -->
                        <div class="form-group">
                            <label for="inputLimit" class="col-sm-2 control-label">Activity Feed Limit</label>
                            <div class="col-sm-10">
                                <select name="feedLimit" class="form-control" id="inputLimit">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>                                
                                </select>
                            </div>
                        </div>
                        <!-- preference of number of bubbles the user wants to see in their graphic -->
                        <div class="form-group">
                            <label for="bubbleLimit" class="col-sm-2 control-label">Graphic Bubble Limit</label>
                            <div class="col-sm-10">
                                <select name="bubbleLimit" class="form-control" id="bubbleLimit">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>                                
                                </select>
                            </div>
                        </div>
                        <!-- submit -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">submit</button>
                            </div>
                        </div>
                    </form>
                </div>
     
            </div>
        </div>
    </div>
    <?php
    include_once SYSTEM_PATH.'/view/footer.tpl';
    ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= BASE_URL?>/public/js/jquery-1.12.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= BASE_URL?>/public/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <!-- constants for js -->
    <script src="<?= BASE_URL ?>/public/js/constants.js"></script>
    <!-- settings.js -->
    <script src="<?= BASE_URL ?>/public/js/settings.js"></script>
</body>

</html>
