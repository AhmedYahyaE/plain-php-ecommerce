<?php

// This is a PROTECTED ROUTE!


/* TEMPLATE PAGE */



session_start();

$pageTitle = ''; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file

// Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\admin\index.php page
if (isset($_SESSION['Username'])) { // if there's a user stored in the Session, allow the user to access this page
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {
        echo 'Welcome to Manage Page';
    } elseif ($do == 'Add') {

    } elseif ($do == 'Insert') {

    } elseif ($do == 'Edit') {

    } elseif ($do == 'Update') {

    } elseif ($do == 'Delete') {
    
    } elseif ($do == 'Activate') {
    
    }

    include $tpl . 'footer.php';

} else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\admin\index.php page    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request
    header('Location: index.php');
    exit();
}