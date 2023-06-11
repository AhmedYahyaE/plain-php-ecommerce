<?php
// This is a PROTECTED ROUTE!

session_start();

$pageTitle = 'Profile'; // Check eCommerce/includes/templates/header.php file    AND    eCommerce/includes/functions/functions.php file

include 'init.php';



// Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\login.php page
// Making sure the user's Session is active
if (isset($_SESSION['user'])) { // if there's a user stored in the Session, allow the user to access this page
    $getUser = $con->prepare('SELECT * FROM `users` WHERE `Username` = ?');
    $getUser->execute(array($sessionUser)); // $sessionUser variable was declared in eCommerce\init.php
    $info = $getUser->fetch();
    $userid = $info['UserID'];
    // echo $info['Username'];
    ?>

        <h1 class="text-center">My Profile</h1>
        <div class="information block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Information</div>
                    <div class="panel-body">
                        <ul class="list-unstyled">

                            <li><i class="fa fa-unlock-alt fa-fw" aria-hidden="true"></i> <span>Login Name</span>: <?php echo $info['Username'] ?></li>
                            <li><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i> <span>Email</span>: <?php echo $info['Email'] ?></li>
                            <li><i class="fa fa-user       fa-fw" aria-hidden="true"></i> <span>Full Name</span>: <?php echo $info['FullName'] ?></li>
                            <li><i class="fa fa-calendar   fa-fw" aria-hidden="true"></i> <span>Registration Date</span>: <?php echo $info['Date'] ?></li>
                            <li><i class="fa fa-tags       fa-fw" aria-hidden="true"></i> <span>Favorite Category</span>:</li>
                        <ul>
                        <a href="#" class="btn btn-default">Edit Information</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-ads block" id="my-ads"><!--The id attribute is used by anchor in header.php in the dropdown menu in the upper bar-->
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading">My Items</div>
                        <div class="panel-body">
    <?php
                            $myItems = getAllFrom('*', '`items`', '`Item_ID`', "WHERE `Member_ID` = $userid", ''); // Get all items of the current authenticated/logged-in user
                            if (!empty($myItems)) {
                                echo '<div class="row">';
                                    foreach ($myItems as $item) {
                                        echo '<div class="col-sm-6 col-md-3">';
                                            echo '<div class="thumbnail item-box">';
                                                if ($item['Approve'] == 0) { // if the items is not approved yet
                                                    echo '<span class="approve-status">Waiting approval</span>';
                                                }
                                                echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                                echo '<img class="img-responsive" src="img.jpg" alt="random image">';
                                                echo '<div class="caption">';
                                                    echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                                                    echo '<p>' . $item['Description'] . '</p>';
                                                    echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                            } else {
                                echo 'There are no Ads to show, Create <a href="newad.php">New Ad</a>';
                            }
    ?>      
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-comments block">
                    <div class="container">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Latest Comments</div>
                            <div class="panel-body">
    <?php
                                $myComments = getAllFrom('`comment`', '`comments`', '`c_id`', "WHERE `user_id` = $userid", '');
                                if (!empty($myComments)) {

                                    foreach ($myComments as $comment) {
                                        echo '<p>' . $comment['comment'] . '</p>';
                                    }
                                } else {
                                    echo 'There are no comments to show';
                                }
    ?>
                            </div>
                        </div>
                    </div>
                </div>

    <?php

} else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\login.php page
    header('Location: login.php'); // redirect to eCommerce/login.php
    exit();
}



include $tpl . 'footer.php'; 
?>