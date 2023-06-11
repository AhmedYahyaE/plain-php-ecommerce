<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo getTitle() ?></title> <!-- getTitle() function is declared in eCommerce\includes\functions\functions.php -->
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">      <!-- $css variable is declared in eCommerce\init.php -->
        <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">   <!-- $css variable is declared in eCommerce\init.php -->
        <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.min.css">      <!-- $css variable is declared in eCommerce\init.php -->
        <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css"> <!-- $css variable is declared in eCommerce\init.php -->
        <link rel="stylesheet" href="<?php echo $css; ?>front.css">              <!-- $css variable is declared in eCommerce\init.php -->
    </head>
    <body>

        <div class="upper-bar">
            <div class="container">
<?php
                if (isset($_SESSION['user'])) { // If the current user is authenticated/logged-in    // Protected Routes (Protecting Routes)    // this page is already in index.php by include-ing init.php
?>

                    <img class="my-image img-thumbnail img-circle" src="img.jpg" alt="random image">
                    <div class="btn-group my-info text-right">
                        <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?php echo ucfirst($sessionUser) ?> <!-- $sessionUser variable was declared in eCommerce\init.php -->
                            <span class="caret"></span>
                        </span>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="newad.php">New Item</a></li>
                            <li><a href="profile.php#my-ads">My Items</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
<?php
                } else { // if the current user isn't authenticated/logged-in i.e. a Unauthenticated/guest/visitor/logged-out    // means the user is activated (approved) by an admin
?>
                    <a href="login.php"><span class="pull-right">Login/SignUp</span></a>
<?php 
                }
?>
            </div>
        </div>


        <nav class="navbar navbar-inverse">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">HomePage</a>
                    <a class="navbar-brand" href="admin/index.php"><b>Admin Panel</b></a>
                </div>
            
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
<?php                       
                        // $allCats = getAllFrom('*', '`categories`', 'WHERE `parent` = 0', '', '`ID`', 'ASC');
                        $allCats = getAllFrom('*', '`categories`', '`ID`', 'WHERE `parent` = 0', '', 'ASC'); // `parent` = 0 are the MAIN/PARENT categories

                        foreach ($allCats as $cat) { // from functions.php
                            // Use of str_replace is to avoid spaces in categories names (exchanging spaces with dashes -)
                            /* echo '<li><a href="categories.php?pageid='. $cat['ID'] . '&pagename=' . str_replace(' ', '-', $cat['Name']) . '">' . $cat['Name'] . '</a></li>'; */
                            echo '<li><a href="categories.php?pageid='. $cat['ID'] . '">' . $cat['Name'] . '</a></li>';
                        }
?>
                </ul>
                
                
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
                
<?php
    // echo "<pre>MY SESSION ARRAY ELEMENTS ARE: \n", print_r($_SESSION), '(from the Frontend in includes/templates/header.php)</pre>';     
?>