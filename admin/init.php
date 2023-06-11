<?php

// Error Reporting
ini_set('display_erros', 'On');
error_reporting(E_ALL);



// Connection To Database
include 'connect.php';



// Routes
$tpl  = 'includes/templates/'; // Template Directory
$lang = 'includes/languages/'; // Languages directory (Note: including language files MUST be at first and before including any other files)
$func = 'includes/functions/'; // Functions Directory
$css  = 'layout/css/'; // CSS Directory    // $css variable is used in eCommerce\admin\includes\templates\header.php
$js   = 'layout/js/';  // JS Directory     // $js variable is used in eCommerce\admin\includes\templates\footer.php



// Including the important files
include $func . 'functions.php'; // functions file
include $lang . 'english.php'; // Language file (Note: Including language files MUST be at first and before including any other files)
include $tpl  . 'header.php'; // header file

// Including navbar.php in all pages except ones that have $noNavbar variable (like: eCommerce\admin\index.php)
if (!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}