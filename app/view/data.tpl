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
  <link href="<?= BASE_URL ?>/public/css/data.css" rel="stylesheet">
  
  <!-- ajax messages -->
  <link href="<?= BASE_URL ?>/public/css/ajax-messages.css" rel="stylesheet">

  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
  <!-- D3.js -->
  <script type="text/javascript" src="<?= BASE_URL ?>/public/d3/d3.min.js"></script>
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
                    <form id="graph-form" class="form-horizontal">
                        <!-- ajax messag displayed in this paragraph -->
                        <div class="ajax-messages"></div>
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
          <div class="row">
            <div class="col-sm-12" id="graph" data-bubble-limit="<?= $bubbleLimit ?>"></div>
          </div>
        </div>
			<div class="container">
      <div class="row">
        <div class="col-sm-12" id="graph" data-bubble-limit="<?= $bubbleLimit ?>"></div>
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
    <!-- for graph -->
    <script type="text/javascript" src="<?= BASE_URL ?>/public/js/data.js"></script>
    <!-- for form -->
    <script type="text/javascript" src="<?= BASE_URL ?>/public/js/graph-form.js"></script>
</body>

</html>
