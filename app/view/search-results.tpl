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
                <div class="col-xs-8 col-xs-offset-2">
                    <h1><?= $title ?></h1>

                    <h3><?= $resultsMessage ?></h3>
                    <?php
                    if (isset($pageIsValid) && $pageIsValid) {
                    ?>
                    
                    <nav class="text-center">
                        <ul class="pagination">
                            <!--
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            -->
                            <?php
                            $paginationNumberBegin = floor($currentPage / 10) * 10;
                            if ($currentPage % 10 == 0) {
                                $paginationNumberBegin -= 9;
                            } else {
                                $paginationNumberBegin++;
                            }
                            $pageLinkCounter = 0;
                            for ($i = $paginationNumberBegin; $i <= $totalPages; $i++) {
                                $activeClass = '';
                                if ($i == $currentPage) {
                                    $activeClass = 'active';
                                }
                                if ($pageLinkCounter == 0) {
                                    if ($paginationNumberBegin != 1) {
                                        echo "<li><a href='#' aria-label='Previous' class='prevSetOfPages'><span aria-hidden='true'>&laquo;</span></a></li>";
                                    }
                                }
                                if ($pageLinkCounter == 10) {
                                    echo "<li><a href='#' aria-label='Next' class='nextSetOfPages'><span aria-hidden='true'>&raquo;</span></a></li>";
                                    break;
                                }
                                $url = BASE_URL."/search/{$searchParam}/{$i}";
                                echo "<li class='{$activeClass}'><a href='{$url}' class='pageLink' data-page-number='{$i}'>{$i}</a></li>";
                                $pageLinkCounter++;
                            }
                            ?>
                            <!--
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            -->
                        </ul>
                    </nav>
                    
                    <?php
                    }
                    ?>
                    
                    <?php
                    if (isset($searchIsValid) && $searchIsValid) {
                        if (isset($pageIsValid) && $pageIsValid) {
                            echo "<h4>Page {$currentPage} out of <span id='totalPages' data-total-pages='{$totalPages}'>{$totalPages}</span> for search term <strong id='searchTerm' data-search-term='{$searchParam}'>{$searchParam}</strong></h4>";
                        }
                        if (isset($movieArray)) {
                            foreach ($movieArray as $movie) {
                                $baseUrl = BASE_URL;
                                echo "<p><a href='{$baseUrl}/movie/{$movie['id']}'>{$movie['title']}</a></p>";
                            }
                        }
                    }
                    ?>
                    
                    <?php
                    if (isset($movieArray)) {
                        foreach ($movieArray as $movie) {
                    ?>
                        <p><a href="<?= BASE_URL ?>/movie/<?= $movie['id'] ?>"><?= $movie['title'] ?></a></p>
                    <?php
                        }
                    }
                    ?>
                    
                    <?php
                    if (isset($pageIsValid) && $pageIsValid) {
                    ?>
                    
                    <nav class="text-center">
                        <ul class="pagination">
                            <!--
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            -->
                            <?php
                            $paginationNumberBegin = floor($currentPage / 10) * 10;
                            if ($currentPage % 10 == 0) {
                                $paginationNumberBegin -= 9;
                            } else {
                                $paginationNumberBegin++;
                            }
                            $pageLinkCounter = 0;
                            for ($i = $paginationNumberBegin; $i <= $totalPages; $i++) {
                                $activeClass = '';
                                if ($i == $currentPage) {
                                    $activeClass = 'active';
                                }
                                if ($pageLinkCounter == 0) {
                                    if ($paginationNumberBegin != 1) {
                                        echo "<li><a href='#' aria-label='Previous' class='prevSetOfPages'><span aria-hidden='true'>&laquo;</span></a></li>";
                                    }
                                }
                                if ($pageLinkCounter == 10) {
                                    echo "<li><a href='#' aria-label='Next' class='nextSetOfPages'><span aria-hidden='true'>&raquo;</span></a></li>";
                                    break;
                                }
                                $url = BASE_URL."/search/{$searchParam}/{$i}";
                                echo "<li class='{$activeClass}'><a href='{$url}' class='pageLink' data-page-number='{$i}'>{$i}</a></li>";
                                $pageLinkCounter++;
                            }
                            ?>
                            <!--
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            -->
                        </ul>
                    </nav>
                    
                    <?php
                    }
                    ?>
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
    <?php
    if (!isset($_SESSION['username']) || $_SESSION['username'] === '') {
    ?>
    <script src="<?= BASE_URL ?>/public/js/login.js"></script>
    <?php
    }
    ?>
    <!-- search results -->
    <script type="text/javascript" src="<?= BASE_URL ?>/public/js/search.js"></script>
</body>

</html>
