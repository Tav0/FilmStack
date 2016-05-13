<?php

include_once '../global.php';

// get the identifier for the action we want
$action = $_GET['action'];

// instantiate a ReviewController and route it
include_once 'ReviewController.class.php';
$rc = new ReviewController();
$rc->route($action);
