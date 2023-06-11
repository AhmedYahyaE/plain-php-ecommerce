<?php

// This is a PROTECTED ROUTE!

    /*
    **ADMIN Manage Comments Page
    *** You can Edit | Delete | Approve Comments from here
    */


    ob_start(); // For fixing the "headers already sent" error (before session_start() function)

    

    session_start();

    $pageTitle = 'Comments'; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file

    // Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\admin\index.php page
    if (isset($_SESSION['Username'])) { // if there's a user stored in the Session, allow the user to access this page    // means if there is a session active (user is already logged in), don't show him the form login page and redirect him to dashboard.php
        include 'init.php';
        // echo '<pre>', print_r($_SESSION), ' (from members.php)</pre>';
        
        // GET HTTP Request ($_GET) is coming from navbar.php (Edit Profile)
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';/* This is the same as:  if (isset($_GET['do'])) {$do = $_GET['do'];} else {$do = 'Manage';} */
        

        


        if ($do == 'Manage') { // GET Request: Manage Members Page (coming from clicking on Members in the navbar.php)
            $stmt = $con->prepare('SELECT `comments`.*, `items`.`Name` AS My_item_name, `users`.`Username` AS My_user_name FROM `comments`
                                    INNER JOIN `items` ON `items`.`Item_ID` = `comments`.`item_id`
                                    INNER JOIN `users` ON `users`.`UserID`  = `comments`.`user_id`
                                    ORDER BY `c_id` DESC
            ');

            // Executing the statement
            $stmt->execute();

            // Retrieving/Fetching all the rows and assigning them to a variable
            $comments = $stmt->fetchAll();
            // echo '<pre>', var_dump($rows), '</pre>';
            //echo '<pre>', print_r($rows), '</pre>';

            if (!empty($comments)) {
?>
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>Username</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
<?php
                        foreach ($comments as $comment) {
                            echo '<tr>';
                                echo '<td>' . $comment['c_id']   . '</td>';
                                echo '<td>' . $comment['comment'] . '</td>';
                                echo '<td>' . $comment['My_item_name']    . '</td>'; // from the last SQL query (AS ....)
                                echo '<td><a href="members.php?do=Edit&userid=' . $comment['user_id'] . '">' . $comment['My_user_name'] . '</a></td>'; // from the last SQL query (AS ....)
                                echo '<td>' . $comment['comment_date'] . '</td>';
                                echo '<td>
                                            <a href="comments.php?do=Edit&comid='   . $comment['c_id'] . '"class="btn btn-success       "><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                            <a href="comments.php?do=Delete&comid=' . $comment['c_id'] . '"class="btn btn-danger confirm"><i class="fa fa-times"           aria-hidden="true"></i> Delete</a>';
                                            if ($comment['status'] == 0) { // means that the user request hasn't been appproved yet, so show the activate button to Admin to make `RegStatus` = 1
                                                echo '
                                                        <a href="comments.php?do=Approve&comid=' . $comment['c_id'] . '"class="btn btn-info activate"><i class="fa fa-check" aria-hidden="true"></i> Approve</a> <!-- Creating a line break/newline after the echo to be just like the previous two buttons (Edit and Delete Buttons) having line breaks/newlines between them, to avoid that the Approve Button becoming closely adhered to the Delete Button (no margin between them!). I used "View Page Source" to solve this problem arising because of using the if condition with the Approve Button, which leads to creating the Activate Button <a> HTML element without a line break/newline between it and its preceding Delete Button HTML element! -->
                                                    ';
                                            }
                                echo '</td>';
                            echo '</tr>';
                        }
?>

                    </table>
                </div>
            </div>
<?php
            } else {
                echo '<div class="container">';
                    echo '<div class="nice-message">There are no comments to show</div';
                echo '</div>';
            }
?>





<?php
        } elseif ($do == 'Edit') { // Edit Page ( // GET HTTP Request($_GET) is coming from Edit Button in comments.php
            // echo 'Welcome to Edit Page. Your Comment ID is ' . $_GET['comid'] . '<br>';

            // Checking if userid GET Request is numeric only and getting its integer value
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            
            // Selecting all data depending on that $comid
            $stmt = $con->prepare('SELECT * FROM `comments` WHERE `c_id` = ?'); // `GroupID` = 1 to make sure the user is an Admin
            // echo '<pre>', print_r($stmt), '</pre>';

            // Executing the query
            $stmt->execute(array($comid));

            // Retrieving/Fetching data resulted from the query
            $row = $stmt->fetch();

            // Get the row count
            $count = $stmt->rowCount(); // returns the number of rows affected by the last SQL statement(from execute())

            // If there's such $userid, show the form
            if ($count > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
?>
                <h1 class="text-center">Edit Comment</h1>
                <div class="container">
                    <!--The form send data to the same page: members.php but with different data: do=Update and so on-->
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $userid TOO THROUGH THE FORM TO THE UPDATE PAGE-->
                        <input type="hidden" name="comid" value="<?php echo $comid ?>">
                        <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $userid TOO THROUGH THE FORM TO THE UPDATE PAGE-->
                        <!-- Start: Comment field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Comment:</label>
                            <div class="col-sm-10 col-md-6">
                                <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                            </div>
                        </div>
                        <!-- End: Comment field -->
                        <!-- Start: Submit field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                    <input class="btn btn-primary btn-lg" type="submit" value="Save">
                            </div>
                        </div>
                        <!-- End: Submit field -->
                    </form>
                </div>
<?php       
            // If there's no such $userid, show Error Message
            } else {
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger"><strong>ERROR: There\'s no such ID</strong></div><br>';
                redirectHome($theMsg);
                echo '</div>';
            }




        } elseif ($do == 'Update') { // Update Page (GET Request coming from the Form in Edit Page)
            echo '<h1 class="text-center">Update Comment</h1>';
            echo '<div class="container">'; // To show errors using Bootstrap (alert-danger class)
            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the URL is not copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)    // if the HTML Form is submitted with a POST method/verb HTTP Request
                // Getting the variables and their values coming from the Form in Edit Page (from name and value attributes in the <input> fields (Ex: <input class="form-control" type="text" name="full" value="<?php echo $row['FullName']">)
                // Storing the name attribute values of the <input> fields in New variables
                $comid   = $_POST['comid']; // from the hidden input field in Edit Page
                $comment = $_POST['comment'];

                // Updating the database corresponding to these info
                $stmt = $con->prepare('UPDATE `comments` SET `comment` = ? WHERE `c_id` = ?');
                $stmt->execute(array($comment, $comid));
    
                //Echoing a Success Message
                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Updated Successfully.</div><br>';

                redirectHome($theMsg, 'back');
            } else {

                $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar, you must come through a POST or GET HTTP Request.</div><br>';
                redirectHome($theMsg);
            }
            echo '</div>'; // The continer div





        } elseif ($do == 'Delete') { // Delete Comment Page (from Delete button in Comments Page)
            echo '<h1 class="text-center">Delete Comment</h1>';
            echo '<div class="container">';
                // Checking if userid GET Request is numeric only and getting its integer value
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
                
                //Checking if the comment exists in the database
                $check = checkItem('`c_id`', '`comments`', $comid);
        
                // If there's such $userid, show the form
                if ($check > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    // echo 'Good this is the form';
                    $stmt = $con->prepare('DELETE FROM `comments` WHERE `c_id` = :zid');
                    $stmt->bindParam(':zid', $comid);
                    $stmt->execute();

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';




            
        } elseif ($do == 'Approve') { // coming from the GET Request of the Approve Button in Manage Comments Page
            echo '<h1 class="text-center">Approve Comment</h1>';
            echo '<div class="container">';
                // Checking if userid GET Request is numeric only and getting its integer value
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
                
                // Checking if the comment exists in the database
                $check = checkItem('`c_id`', '`comments`', $comid);
                
                // If there's such $comid, show the form
                if ($check > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    $stmt = $con->prepare('UPDATE `comments` SET `Status` = 1 WHERE `c_id` = ?');
                    $stmt->execute(array($comid));

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Comment Approved Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';
        }





        // Include footer.php
        include $tpl . 'footer.php';

        
    } else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\admin\index.php page    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request
        // echo 'You Are Not Authorized to View This Page (from dashboard.php)<br>';
        header('Location: index.php');
        exit();
    }



    ob_end_flush(); // For fixing the "headers already sent" error