<?php

include_once '../global.php';

// get the identifier for the action we want
$action = $_GET['action'];

// instantiate a AccountController and route it
include_once 'AccountController.class.php';
$ac = new AccountController();
$ac->route($action);
