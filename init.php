<?php

// Error Reporting
ini_set('display_erros', 'On');
error_reporting(E_ALL);



// Connection To Database
include 'admin/connect.php';



$sessionUser = ''; // This variable will be used in various files! (e.g. eCommerce\includes\templates\header.php) Check its References (usages) by clicking Shift + F12 in Visual Studio Code
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user']; // This variable will be used in various files! (e.g. eCommerce\includes\templates\header.php) Check its References (usages) by clicking Shift + F12 in Visual Studio Code
}



// Routes
$tpl  = 'includes/templates/'; // Templates Directory
$lang = 'includes/languages/'; // Languages directory (Note: including language files MUST be at first and before including any other files)
$func = 'includes/functions/'; // Functions Directory
$css  = 'layout/css/'; // CSS Directory    // $css variable is used in eCommerce\includes\templates\header.php
$js   = 'layout/js/';  // JS Directory     // $js variable is used in eCommerce\includes\templates\footer.php


// Including the important files
include $func . 'functions.php'; // functions file
include $lang . 'english.php'; // Language file (Note: including language files MUST be at first and before including any other files)
include $tpl  . 'header.php'; // header file    // this files includes navbar Markup too (we merged header.php & navbar.php into one file in the frontend)