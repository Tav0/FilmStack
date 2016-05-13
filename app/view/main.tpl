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
  <link href="<?= BASE_URL ?>/public/css/feed.css" rel="stylesheet">
  <!-- home feed -->
  <link href="<?= BASE_URL ?>/public/css/home-feed.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/profile.css" rel="stylesheet">
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
        <div class="row">
          <div class="col-xs-12">
            <h1><?= $title ?></h1>
            <?php
                if (isset($_SESSION['userid']) && $_SESSION['userid'] !== '') {
                ?>
              <h3>Activity Feed</h3>
              <div id="profile-feed" class="container">
                <div id="home-feed">
                  <?= $homeFeedHtml ?>
                </div>
              </div>
              <?php
                }
                ?>
          </div>
        </div>

      </div>
    </div>
        <!-- BEGIN upcoming movies -->
        <?php
        include_once SYSTEM_PATH.'/view/movie_carousel.tpl';
        ?>
        <!-- END upcoming movies -->
        <!-- BEGIN genres -->
            <?php
            $apiHandler = new APIHandler();
            $genres = $apiHandler->getGenres();
            $arr_genres = $genres['genres'];
            $initial_amount = count($arr_genres);

            $id_genre = '';
            $name_genre = '';
            $genre_arr = '';
            $genreList = '';

            for ($nArr = 0; $nArr < $initial_amount; $nArr++) {
                $id_genre = $arr_genres[$nArr]['id'];
                $name_genre = $arr_genres[$nArr]['name'];
                $genre_arr = $apiHandler -> searchGenre($id_genre);
                $genreList = $genre_arr['results'];
                include SYSTEM_PATH.'/view/genres_carousel.tpl';
            }
        ?>
        <!-- END genres -->
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
    <!-- for login ajax -->
    <script src="<?= BASE_URL ?>/public/js/login.js"></script>
</body>

</html>
