<?php

// This is a PROTECTED ROUTE!

session_start();

// Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\admin\index.php page
if (isset($_SESSION['Username'])) { // if there's a user stored in the Session, allow the user to access this page    // means if there is a session active (user is already logged in), don't show him the form login page and redirect him to dashboard.php
        $pageTitle = 'Dashboard'; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file


        include 'init.php';



        /* Start: Dashboard Page <body> */
        //echo 'Welcome ' . $_SESSION['Username'] . ' (from dashboard.php)<br>'; 
        // echo "<pre>MY SESSION ARRAY ELEMENTS ARE: \n", print_r($_SESSION), ' (from dashboard.php)</pre>';

        $numUsers = 6; // The number of latest users (in latest users div)
        $latestUsers = getLatest('*', 'users', 'UserID', $numUsers); // Latest Users Array

        $numItems = 5; // The number of latest items (in latest items div)
        $latestItems = getLatest('*', 'items', 'Item_ID', $numItems); // Latest Items Array

        $numComments = 5; // The number of latest comments
        $latestComments = getLatest('*', 'items', 'Item_ID', $numItems); // Latest Items Array

    ?>
        <div class="home-stats">
            <div class="container text-center"><!--class home-stats will take any CSS edits but will be used only as a parent-->
                <h1>Dashboard</h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat st-members">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="info">
                                Total Members <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-pending">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            <div class="info">
                                Pending Members <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus', 'users', 0) ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-items">
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            <div class="info">
                                Total Items <span><a href="items.php"><?php echo countItems('Item_ID', 'items') ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-comments">
                            <i class="fa fa-comments" aria-hidden="true"></i>
                            <div class="info">
                                Total Comments <span><a href="comments.php"><?php echo countItems('c_id', 'comments') ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="latest">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users" aria-hidden="true"></i> Latest <span style='color:red; font-weight:bold;font-size:20px'><?php echo $numUsers ?></span> Registered Users <span class="pull-right toggle-info"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
    <?php
                                    if (!empty($latestUsers)) {
                                        foreach ($latestUsers as $user) {
                                            echo '<li>';
                                                echo $user['Username'];
                                                echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                                    echo '<span class="btn btn-success pull-right">';
                                                        echo '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>';
                                                        if ($user['RegStatus'] == 0) { // means that the user request hasn't been appproved yet, so show the activate button to Admin to make RegStatus = 1
                                                            echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '" class="btn btn-info pull-right activate"><i class="fa fa-check" aria-hidden="true"></i> Activate</a>';
                                                        }
                                                    echo '</span>';
                                                echo '</span>';
                                            echo '</li>';
                                        }
                                    } else {
                                        echo 'There are no members to show';
                                    }
    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag" aria-hidden="true"></i> Latest <span style='color:red; font-weight:bold;font-size:20px'><?php echo $numItems ?></span> Items<span class="pull-right toggle-info"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
    <?php
                                    if (!empty($latestItems)) {
                                        foreach ($latestItems as $item) {
                                            echo '<li>';
                                                echo $item['Name'];
                                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                                    echo '<span class="btn btn-success pull-right">';
                                                        echo '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>';
                                                        if ($item['Approve'] == 0) { // means that the user request hasn't been appproved yet, so show the approve button to Admin to make Approve = 1
                                                            echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class="btn btn-info pull-right activate"><i class="fa fa-check" aria-hidden="true"></i> Approve</a>';
                                                        }
                                                    echo '</span>';
                                                echo '</span>';
                                            echo '</li>';
                                        }
                                    } else {
                                        echo 'There are no items to show';
                                    }
    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Start: Latest Comments-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comments-o fa-lg" aria-hidden="true"></i> Latest <span style='color:red; font-weight:bold; font-size:20px'><?php echo $numComments ?></span> Comments <span class="pull-right toggle-info"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></span> <!-- Check backend.js -->
                            </div>
                            <div class="panel-body">
    <?php
                                $stmt = $con->prepare("SELECT `comments`.*, `users`.`Username` AS My_user_name FROM `comments`
                                                        INNER JOIN `users` ON `users`.`UserID` = `comments`.`user_id`
                                                        ORDER BY `c_id` DESC
                                                        LIMIT $numComments
                                ");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                                if (!empty($comments)) {
                                    foreach ($comments as $comment) {
                                        echo '<div class="comment-box">';
                                            echo '<span class="member-n"><a href="members.php?do=Edit&userid=' . $comment['user_id'] . '">' . $comment['My_user_name'] . '</a></span>';
                                            echo '<p    class="member-c">' . $comment['comment']      . '</p>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo 'There are no comments to show';
                                }
    ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End: Latest Comments-->
            </div>
        </div>








    <?php
        include $tpl . 'footer.php';


} else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\admin\index.php page    // This is for security to prevent anyone from copy/paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request
    // echo 'You Are Not Authorized to View This Page (from dashboard.php)<br>';
    header('Location: index.php');
    exit();
}