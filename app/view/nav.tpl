<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="glyphicon glyphicon-tasks navbar-brand" href="<?= BASE_URL ?>">
        <span style="margin-left: 10px;font-size: 1em;font-weight: 600;">FilmStack</span>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="nav navbar-nav navbar-right">
		<li><a href="<?= BASE_URL ?>/genres/">Genres</a></li>
        <li><a href="<?= BASE_URL ?>/credits">Credits</a></li>
        <li class="dropdown">
          <?php
                if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
                // user is not logged in
                ?>
            <a href="#" id="loginDropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="glyphicon glyphicon-log-in"></span> Login
            </a>
            <ul class="dropdown-menu">
              <form id="loginForm">
                <div class="col-sm-12">
                  <input name="username" type="text" placeholder="username" class="form-control input-sm" />
                </div>
                <br/>
                <div class="col-sm-12">
                  <input name="password" type="password" placeholder="password" class="form-control input-sm" />
                </div>
                <br/>
                <div class="col-sm-12">
                  <button type="submit" class="btn btn-sm btn-default btn-block">login</button>
                </div>
                <div class="col-sm-12">
                  <p class="ajax-message"></p>
                </div>
              </form>
              <li><a href="<?= BASE_URL ?>/signup"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
            </ul>
            <?php
                } else {
                // user is logged in
                ?>
              <a href="#" id="loginDropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user"></span>
                <span class="text-capitalize"><?= $_SESSION['username'] ?></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="<?= BASE_URL ?>/profile/<?= $_SESSION['userid'] ?>">Profile</a></li>
                <li><a href="<?= BASE_URL ?>/list/<?= $_SESSION['userid'] ?>">List</a></li>
				<?php if(isset($_SESSION['userid']) && $_SESSION['userid'] !== "") { ?>
					<li><a href="<?= BASE_URL ?>/graph">Graph</a></li>
				<?php if(isset($_SESSION['type']) && $_SESSION['type'] === "Moderator"){ ?>
					<li><a href="<?= BASE_URL ?>/moderator">Moderator</a></li>
				<?php }} ?>
                <li><a href="<?= BASE_URL ?>/settings">Settings</a></li>
                <li><a href="<?= BASE_URL ?>/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
              <?php
                }
                ?>
        </li>
      </ul>
      <form action="<?= BASE_URL ?>/search" method="GET" class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" name="search-input" class="form-control" placeholder="Search movies">
        </div>
        <button type="submit" class="btn btn-default text-capitalize">search</button>
      </form>
    </div>
  </div>
</nav>
