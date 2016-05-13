<?php

include_once '../global.php';

// get the identifier for the action we want
$action = $_GET['action'];

// instantiate a MovieController and route it
include_once 'MovieController.class.php';
$mc = new MovieController();
$mc->route($action);
