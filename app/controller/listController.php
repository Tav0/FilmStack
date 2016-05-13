<?php

include_once '../global.php';

// get the identifier for the action we want
$action = $_GET['action'];

// instantiate a ListController and route it
include_once 'ListController.class.php';
$lc = new ListController();
$lc->route($action);
