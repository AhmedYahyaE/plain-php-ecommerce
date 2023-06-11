<?php

session_start();



session_unset(); // Unsetting the data (or the session variable)
session_destroy(); // Destroying the session



echo "You have logged out and will be redirected after 3 secondes...";
// header('Location: index.php');



header('REFRESH:3; URL=index.php'); // to be headed/redirected to another page after a certain time duration you exactly want    // Redirect to eCommerce\index.php
exit();
