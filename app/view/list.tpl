<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">

	<!-- for profile buttons, needs to be up here -->
	<script type="text/javascript" src="<?= BASE_URL ?>/public/js/list.js"></script>
	
	<title>
		<?= $title ?>
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
	
	<div class="col-sm-12" id="content">
	<div class="container">
		<h2 id="profile_headline">Movies In Your List</h2>
		<p class="ajax-message"></p>
		
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#all">All</a></li>
			<li><a data-toggle="tab" href="#towatch">To Watch</a></li>
			<li><a data-toggle="tab" href="#watched">Watched</a></li>
		</ul>
		
		<div class="tab-content">
			<div id="all" class="tab-pane in active">
				<div class="container">
					<?= $all ?>
				</div>
			</div>
			<div id="towatch" class="tab-pane">
				<div class="container" id="watchContainer">
					<?= $toWatchString ?>
				</div>
			</div>
			<div id="watched" class="tab-pane">
				<div class="container" id="watchedContainer">
					<?= $watchedString ?>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	
	</div>
    <?php
    include_once SYSTEM_PATH . '/view/footer.tpl';
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
