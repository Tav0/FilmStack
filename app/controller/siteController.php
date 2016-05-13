<?php

include_once '../global.php';

// get the identifier for the action we want
$action = $_GET['action'];

// instantiate a SiteController and route it
include_once 'SiteController.class.php';
$sc = new SiteController();
$sc->route($action);
