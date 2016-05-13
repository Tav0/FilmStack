<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <title>
    <?= $title ?>
  </title>

  <!-- Bootstrap -->
  <link href="<?= BASE_URL ?>/public/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/carousel.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/profile.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/feed.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/footer.css" rel="stylesheet">

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

    <div class="col-sm-12" id="content">
      <div class="container">
        <div class="col-xs-12">
          <h2 id="profile_headline"><?= $title ?></h2>
        </div>
        <div class="col-md-4 text-center text-capitalize">
          <h4><?= $username ?></h4>
        </div>

        <div class="col-md-8 text-center">
          <?php
				if($same) {
				?>
            <?= $userInformation ?>
              <?php
				}
				?>
                <div class="col-md-3">
                  <a id="profileList" href="<?= BASE_URL ?>/list/<?= $userID ?>">
                    <?= $toWatchCount ?> Movies To Watch,
                      <?= $watchedCount ?> Movies Watched
                  </a>
                </div>
        </div>

        <div class="row">
          <?php
                // do not display follow button if user is viewing their own profile
                if ($_SESSION['userid'] !== $_GET['profileid']) {
                ?>
            <div class="col-xs-12" id="buttonWrapper">
              <?php
					if($loggedIn && !$same)
						if ($relationshipExists) {
                    ?>
                <!-- profile.js will depend on #followButton for AJAX -->
                <button id="unfollowButton" data-profile-id="<?= $userID ?>" class="btn btn-lg btn-warning btn-block active">Unfollow</button>
                <?php
						} else {
                    ?>
                  <!-- profile.js will depend on #followButton for AJAX -->
                  <button id="followButton" data-profile-id="<?= $userID ?>" class="btn btn-lg btn-primary btn-block">Follow</button>
                  <?php
						}
                    ?>
            </div>
            <?php
                }
                ?>
              <p class="ajax-message"></p>
        </div>
        <div id="profile-feed" class="container">
          <div class="col-sm-6" id="home-feed">
            <?= $profileFeedHtml ?>
          </div>
        </div>
        <div id="following-tabs-container" class="col-xs-12">
          <ul class="nav nav-tabs text-capitalize">
            <li class="active">
              <a data-toggle="tab" href="#leaders">People <?= $username ?> Follows</a>
            </li>
            <li>
              <a data-toggle="tab" href="#followers">People Following <?= $username ?></a>
            </li>
          </ul>
          <div class="tab-content text-capitalize">
            <div id="leaders" class="tab-pane in active">
              <p id="profile-leader-list-placeholder">This person is a bit shy right now</p>
              <div class="container" id="leadersContainer">
                <?= $leaders ?>
              </div>
            </div>
            <div id="followers" class="tab-pane">
              <p id="profile-follow-list-placeholder">Still looking for people to follow...</p>
              <div class="container" id="followersContainer">
                <?= $followers ?>
              </div>
            </div>
          </div>
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
    <!-- for follow button ajax -->
    <script src="<?= BASE_URL ?>/public/js/profile.js"></script>
    <!-- for login ajax -->
    <script src="<?= BASE_URL ?>/public/js/login.js"></script>
</body>

</html>
