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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
    <!-- nav -->
    <?php
    include_once SYSTEM_PATH.'/view/nav.tpl';
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-md-offset-3">
                <form id="signupForm">
                    <h2>Sign Up</h2>
                    <!-- Username -->
                    <label for="inputUsername" class="sr-only">Username</label>
                    <input name="username" type="text" id="inputUsername" class="form-control" placeholder="Username">
					<!-- First Name -->
					<label for="inputFirstName" class="sr-only">First Name</label>
                    <input name="firstName" type="text" id="inputFistName" class="form-control" placeholder="First Name">
					<!-- Last Name -->
					<label for="inputLastName" class="sr-only">Last Name</label>
                    <input name="lastName" type="text" id="inputLastName" class="form-control" placeholder="Last Name">
                    <!-- Email -->
                    <label for="inputEmail" class="sr-only">email address</label>
                    <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email">
                    <!-- Password -->
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password">
                    <!-- submit button -->
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                    <!-- ajax-message -->
                    <p class="ajax-message"></p>
                </form>
            </div>
        </div>
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= BASE_URL?>/public/js/jquery-1.12.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= BASE_URL?>/public/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <!-- constants for js -->
    <script src="<?= BASE_URL ?>/public/js/constants.js"></script>
    <!-- for login ajax -->
    <script src="<?= BASE_URL ?>/public/js/login.js"></script>
    <!-- email validation helper -->
    <script src="<?= BASE_URL ?>/public/js/emailValidator.js"></script>
    <!-- for signup ajax -->
    <script src="<?= BASE_URL ?>/public/js/signup.js"></script>
  </body>
</html>
