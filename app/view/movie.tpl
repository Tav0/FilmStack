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
  <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/public/css/nav.css" rel="stylesheet">

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
        <div class="slide">
            <div class="container">
          <div class="row">
              <div class="col-md-12">
                <h1 id="movieName"><?= $movieTitle ?></h1>
              </div>


              <div class="col-md-6 text-center">
                <img class="movie-poster" src="<?= $posterUrl ?>" alt="poster for movie <?= $movieTitle ?>" />
              </div>

              <div class="col-md-6">
                <h3>Overview</h3>
                <p>
                  <?= $overview ?>
                </p>
                            <?= $button ?>
              </div>

              <div class="col-md-6">
                <h3>Release Date</h3>
                <p>
                  <?= $releaseDate ?>
                </p>
              </div>

              <div class="col-md-6">
                <h3>Genre(s)</h3>
                <?php
                    foreach($genres as $genre) {
                    ?>
                  <a href="<?= BASE_URL ?>/genre/<?= $genre['id'] ?>">
                    <?= $genre['name'] ?>
                  </a>
                  <?php
                    }
                    ?>
              </div>

              <div class="col-md-6">
                <h3>Popularity</h3>
                <p>
                  <?= $popularity ?>
                </p>
              </div>
            </div>
          </div>
          
          <!--Removed extra slide div here for future note-->
          
        <div class="container">
          <div class="row">
            <?php if(trim($backdropUrl) != "http://image.tmdb.org/t/p/original") { ?>
            <div class="col-md-12">
              <h3>Backdrop</h3>
              <img class="movie-poster" src="<?= $backdropUrl ?>" alt="backdrop for movie <?= $movieTitle ?>" />
            </div>
            <?php } ?>
            
            <?php  if(count($productionCompanies) !== 0) { ?>
            <div class="col-md-12">
              <h3>Production Companies</h3>
              <?php
                    foreach($productionCompanies as $productionCompany) {
                    ?>
                <p>
                  <?= $productionCompany['name'] ?>
                </p>
                <?php
                            }
                        ?>
            </div>
            <?php } ?>

            <?php if(count($productionCountries) !== 0) { ?>
            <div class="col-md-12">
              <h3>Production Countries</h3>
              <?php
                    foreach($productionCountries as $productionCountry) {
                    ?>
                <p>
                  <?= $productionCountry['name'] ?>
                </p>
                <?php
                    }
                    ?>
            </div>
            <?php } ?>


            <?php if(trim($revenue) != "") { ?>
            <div class="col-md-12">
              <h3>Revenue</h3>
              <p>$
                <?= $revenue ?>
              </p>
            </div>
            <?php } ?>

            <?php if(trim($runtime) != "") { ?>
            <div class="col-md-12">
              <h3>Runtime</h3>
              <p>
                <?= $runtime ?> min</p>
            </div>
            <?php } ?>

            <?php if(trim($tagline) != "") { ?>
            <div class="col-md-12">
              <h3>Tagline</h3>
              <p>
                <?= $tagline ?>
              </p>
            </div>
            <?php } ?>
            
            <?php if(trim($toWatch) != "") { ?>
            <div class="col-md-12">
                <h3>Users who want to watch this movie</h3>
                <?= $toWatch ?>
            </div>
            <?php } ?>
            
            <?php if(trim($watched) != "") { ?>
            <div class="col-md-12">
                <h3>Users watched this movie</h3>
                <?= $watched ?>
            </div>
            <?php } ?>

            <div>
            <?php
                    if (isset($_SESSION['username']) && $_SESSION['username'] != '') 
                    {
                      echo '<div class="col-md-12" id="commentSection">
                <h3>Enter a Review</h3>
                <textarea id="enterComments" placeholder="Enter your comments"></textarea>
                <div class="dropup">
                  <button id="endorseButton" class="commentButton">Endorse</button>
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Rating <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <li><a href="#">1 Star</a></li>
                    <li><a href="#">2 Star</a></li>
                    <li><a href="#">3 Star</a></li>
                    <li><a href="#">4 Star</a></li>
                    <li><a href="#">5 Star</a></li>
                  </ul>
                  <button id="submitComment" class="commentButton" type="submit" value="Submit">Submit</button>
                </div>
              </div>';
                    }
                  ?>
              <div class="col-md-12">
                <h3>Reviews</h3>
                <div id="commentList">
                  <?= $commentDetails ?>
                </div>
                <p id="reviews_placeholder">Be the first to review this movie!</p>
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
    <!-- for login ajax -->
    <script src="<?= BASE_URL ?>/public/js/login.js"></script>
	<!-- for add to list -->
    <script src="<?= BASE_URL ?>/public/js/movie.js"></script>
  </body>
</html>
