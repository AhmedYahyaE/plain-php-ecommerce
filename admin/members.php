<?php

    // This is a PROTECTED ROUTE!

    /*
    **ADMIN Manage Members Page
    *** You can Add | Insert | Edit | Update | Delete Members from here
    */

    ob_start(); // For fixing the "headers already sent" error (before session_start() function)



    session_start();

    $pageTitle = 'Members'; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file

    // Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\admin\index.php page
    if (isset($_SESSION['Username'])) { // if there's a user stored in the Session, allow the user to access this page    // means if there is a session active (user is already logged in), don't show him the form login page and redirect him to dashboard.php
        include 'init.php';
        // echo '<pre>', print_r($_SESSION), ' (from members.php)</pre>';

        // HTTP Request($_GET) is coming from navbar.php (Edit Profile)
        // Determine the value of $do    // $do is a Query String Parameter e.g.    members.php?do=Add
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; /* This is the same as:  if (isset($_GET['do'])) {$do = $_GET['do'];} else {$do = 'Manage';} */
        

        
        
        // Show all members
        if ($do == 'Manage') { // the main members.php page without any Query String Parameters i.e.    /eCommerce/admin/members.php    // Manage Members Page (coming from clicking on Members in the navbar.php)
            // echo 'Welcome to Manage Members Page<br>';


            // Build the SQL query
            $query = '';

            if (isset($_GET['page']) && $_GET['page'] == 'Pending') { // GET Request from the Pending Members <a> anchor in dashboard.php page
                $query = ' AND `RegStatus` = 0'; // add that to the query to show the pending members ONLY    // The `RegStatus` column in `users` table:    0 zero value denotes the pending members (the not yet approved members), and    1 one value denotes the accepted/approved members
            }

            // Selecting all users except for ADMINS (`GroupID` = 1)
            $stmt = $con->prepare("SELECT * FROM `users` WHERE `GroupID` != 1 $query ORDER BY `UserID` DESC"); // Show all users EXCEPT FOR Admins (and in case u r coming from Pending Members <a> in dashboard.php, add that $query to show the pending members ONLY)

            // Executing the statement
            $stmt->execute();

            // Retrieving/Fetching all the rows and assigning them to a variable
            $rows = $stmt->fetchAll();
            // echo '<pre>', var_dump($rows), '</pre>';
            // echo '<pre>', print_r($rows), '</pre>';

            if (!empty($rows)) {
?>
                <h1 class="text-center">Manage Members</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table manage-members text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Avatar</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Registration Date</td>
                                <td>Control</td>
                            </tr>
<?php
                            foreach ($rows as $row) {
                                echo '<tr>';
                                    echo '<td>' . $row['UserID']   . '</td>';
                                    echo '<td>';
                                        if (empty($row['avatar'])) {
                                            echo 'No Image';
                                        } else {
                                            echo '<img src="uploads/avatars/' . $row['avatar'] . '" alt="avatar">';
                                        }
                                    echo '</td>';
                                    echo '<td>' . $row['Username'] . '</td>';
                                    echo '<td>' . $row['Email']    . '</td>';
                                    echo '<td>' . $row['FullName'] . '</td>';
                                    echo '<td>' . $row['Date'] . '</td>';
                                    echo '<td>
                                                <a href="members.php?do=Edit&userid='   . $row['UserID'] . '"class="btn btn-success       "><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                                <a href="members.php?do=Delete&userid=' . $row['UserID'] . '"class="btn btn-danger confirm"><i class="fa fa-times"           aria-hidden="true"></i> Delete</a>';
                                                if ($row['RegStatus'] == 0) { // means that the user request hasn't been appproved yet, so show the activate button to Admin to make RegStatus = 1
                                                    echo '
                                                            <a href="members.php?do=Activate&userid=' . $row['UserID'] . '"class="btn btn-info activate"><i class="fa fa-check" aria-hidden="true"></i> Activate</a> <!-- Creating a line break/newline after the echo to be just like the previous two buttons (Edit and Delete Buttons) having line breaks/newlines between them, to avoid that the Approve Button becoming closely adhered to the Delete Button (no margin between them!). I used "View Page Source" to solve this problem arising because of using the if condition with the Approve Button, which leads to creating the Activate Button <a> HTML element without a line break/newline between it and its preceding Delete Button HTML element! -->
                                                        ';
                                                }
                                    echo '</td>';
                                echo '</tr>';
                            }
?>

                        </table>
                    </div>
                    <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> New Member</a>
                </div>
<?php
            } else { // if there're no members in the `users` database table
                echo '<div class="container">';
                    echo '<div class="nice-message">There are no members to show</div';
                    echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> New Member</a>';
                echo '</div>';
            }
?>





<?php
            // Add a new member
        } elseif ($do == 'Add') { // i.e.    eCommerce/admin/members.php?do=Add    // Add Members Page (from members.php page from the blue anchor <a> HTML element at the far left bottom of the page)
?>
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <!--The form send data to the same page: members.php but with different Query String Parameters: do=Update and so on-->
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data"><!--This enctype attribute is used when there is Uploading Files-->
                    <!-- Start: Username field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username:</label>
                        <div class="col-sm-10 col-md-6">
                            <input class="form-control" type="text" name="username" autocomplete="off" required="required" placeholder="Your Username to login to site">
                        </div>
                    </div>
                    <!-- End: Username field -->
                    <!-- Start: Password field -->
                    <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password:</label>
                            <div class="col-sm-10 col-md-6">
                                <input class="password form-control" type="password" name="password" autocomplete="new-password" required="required" placeholder="Password must be hard to guess">
                                <i class="show-pass fa fa-eye fa-2x" aria-hidden="true"></i> <!-- The Show Password eye icon (Show the password when hovering over the eye icon). Check eCommerce\admin\layout\js\backend.js -->
                            </div>
                    </div>
                    <!-- End: Password field -->
                    <!-- Start: Email field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email:</label>
                        <div class="col-sm-10 col-md-6">
                            <input class="form-control" type="email" name="email" required="required" placeholder="Email must be valid">
                        </div>
                    </div>
                    <!-- End: Email field -->
                    <!-- Start: FullName field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name:</label>
                        <div class="col-sm-10 col-md-6">
                            <input class="form-control" type="text" name="full" required="required" placeholder="Your Full Name will appear in your profile page">
                        </div>
                    </div>
                    <!-- End: FullName field -->
                    <!-- Start: Avatar field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">User Avatar:</label>
                        <div class="col-sm-10 col-md-6">
                            <input class="form-control" type="file" name="avatar" required="required">
                        </div>
                    </div>
                    <!-- End: Avatar field -->
                    <!-- Start: Submit field -->
                    <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input class="btn btn-primary btn-lg" type="submit" value="Add Member">
                            </div>
                        </div>
                    <!-- End: Submit field -->
                </form>
            </div>




<?php
        } elseif ($do == 'Insert') { // Handle $do == 'Add' HTML Form submission in Add New Member page    // Insert Member Page (coming from the HTML Form in Add Members Page i.e. $do == 'Add')
            // echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['full'];
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the URL is not copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)    // if the HTML Form is submitted with a POST method/verb HTTP Request
                echo '<h1 class="text-center">Insert Member</h1>';
                echo '<div class="container">'; // To show errors using Bootstrap (alert-danger class)
                // Getting the variables and their values coming from the Form in Edit Page (from name and value attributes in the <input> fields (Ex: <input class="form-control" type="text" name="full" value="<?php echo $row['FullName']">)
                // Storing the name attribute values of the <input> fields in New variables

                // Handle Uploading Files (image) variables
                $avatar = $_FILES['avatar']; // coming from the name HTML Attribute in the <form> (upload avatar <input> field) in Add New Member page
                // echo '<pre>', print_r($avatar), '</pre>'; 
                // echo $_FILES['avatar']['name'] . '<br>'; 
                // echo $_FILES['avatar']['size'] . '<br>'; 
                // echo $_FILES['avatar']['tmp_name'] . '<br>'; 
                // echo $_FILES['avatar']['type'] . '<br>'; 

                $avatarName = $_FILES['avatar']['name']; 
                $avatarSize = $_FILES['avatar']['size']; 
                $avatarTmp  = $_FILES['avatar']['tmp_name']; 
                $avatarType = $_FILES['avatar']['type']; 

                // List of ONLY allowed file types (extensions (image extensions)) to upload 
                $avatarAllowedExtensions = array('jpeg', 'jpg', 'png', 'gif');
                
                // Getting Avatar Extension
                $myOwnVar = explode('.', $avatarName); // split the file name on the '.' dot boundary to a file name without extension and the extension. Example: cat.jpg is splitted to cat and jpg    // to avoid the PHP 7.0 Notice
                // echo '<pre>', var_dump($myOwnVar), '</pre>';
                // exit;

                $avatarExtension = strtolower(end($myOwnVar)); // end function: Sets the internal pointer of an array to its last element    // This is equivalent to:    $avatarExtension = strtolower($myOwnVar[1]); // the image extension    // https://www.php.net/manual/en/function.end.php
                // echo $avatarExtension; 

                $user     = $_POST['username'];
                $pass     = $_POST['password']; // Very Important: sha1 considers empty string as a value and not empty and it hashes it... so when sha1 is used with empty() function, empty() never returns true (i.e. never finds it really empty)
                $email    = $_POST['email'];
                $name     = $_POST['full'];
                $hashPass = sha1($pass);

                // HTML Form Server-side Validation
                $formErrors = array(); // Empty array

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>'; 
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>'; 
                }

                if (empty($user)) {
                    $formErrors[] = 'Username can\'t be <strong>empty</strong>'; 
                }

                if (empty($pass)) {
                    $formErrors[] = 'Password can\'t be <strong>empty</strong>'; 
                }

                if (empty($name)) {
                    $formErrors[] = 'Full Name can\'t be <strong>empty</strong>'; 
                }

                if (empty($email)) {
                    $formErrors[] = 'Email can\'t be <strong>empty</strong>'; 
                }

                // Avatar (photo upload) Errors (3 errors)
                if (empty($avatarName)) { // if the user didn't upload a photo
                    $formErrors[] = 'Avater is <strong>required</strong>'; 
                }

                if (!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtensions)) { // if the user uploaded a file with a NOT Allowed extension
                    $formErrors[] = 'This file extension is <strong>Not allowed</strong>'; 
                }

                if ($avatarSize > 4194304) { // if the file size is larger than 4 MB (in bytes not MB)
                    $formErrors[] = 'Avatar size can\'t be larger than <strong>4MB</strong>'; 
                }

                // A loop over/through the array to display the errors
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>'; // Show a Bootstrap error
                }


                // Checking if there are no errors, handling uploading the file, then proceeding to the Add/Insert operation
                if (empty($formErrors)) {
                    $avatar = rand(0, 10000000) . '_' . $avatarName; // rand() is used to prevent the repition of the files/photos names in the database (in the `avatar` column of the `users` table)
                    // echo $avatar; 

                    // Handle Uploading the file (image)
                    move_uploaded_file($avatarTmp, 'uploads\avatars\\' . $avatar); // move and rename at the same time    // Move the uploaded file from the server's path to our dedicated uploads/avatars folder

                    // Checking if the user already exists in the database
                    $check = checkItem('`Username`', '`users`', $user);

                    if ($check == 1) { // this means that the user already exists
                        $theMsg = '<div class="alert alert-danger">Sorry, This user already exists</div><br>';
                        redirectHome($theMsg, 'back');
                    } else {
                        // Inserting User info into database
                        $stmt = $con->prepare('INSERT INTO `users` (`Username`, `Password`, `Email`, `FullName`, `RegStatus`, `Date`, `avatar`) VALUES (:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)');

                        $stmt->execute(array(
                            'zuser'   => $user,
                            'zpass'   => $hashPass,
                            'zmail'   => $email,
                            'zname'   => $name,
                            'zavatar' => $avatar
                        ));

                        // Echoing a Success Message
                        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Inserted Successfully.</div><br>';
                        redirectHome($theMsg, 'back');
                    }
                }

            } else {
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar, you must come through a POST or GET HTTP Request.</div><br>';
                redirectHome($theMsg);
                echo '</div>';
            }

            echo '</div>'; // The .continer CSS class div




        } elseif ($do == 'Edit') { // URL like: http://127.0.0.1/eCommerce/admin/members.php?do=Edit&userid=28    // Edit Member Page (GET HTTP Request coming from the green Edit button in members.php)
            // echo 'Welcome to Edit Page. Your ID is ' . $_GET['userid'] . '<br>';
            /* if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {//This is for security to guarantee that the value entered will be always an integer and not string
                echo intval($_GET['userid']); // This is for security to guarantee that the value entered will be always an integer and not text
            } else {
                echo 0;
            } */
            // Using the Ternary Operator for the same previous if condition code
            // Checking if userid GET Request is numeric only and getting its integer value
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; // Coming from the green Edit button <a> anchor HTML element in members.php page
            
            // Checking if the user exists in the database before anything
            // Selecting all data depending on that $userid
            $stmt = $con->prepare('SELECT * FROM `users` WHERE `UserID` = ? LIMIT 1');
            // echo '<pre>', print_r($stmt), '</pre>';

            // Executing the query
            $stmt->execute(array($userid));

            // Retrieving/Fetching data resulted from the query
            $row = $stmt->fetch();
            // var_dump($stmt->execute(array($username, $hashedPass)));

            // Get the row count
            $count = $stmt->rowCount(); // returns the number of rows affected by the last SQL statement(from execute())

            // If there's such $userid, show the HTML Form
            if ($count > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                // echo 'Good this is the form';
?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <!--The form send data to the same page: members.php but with different data: do=Update and so on-->
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $userid TOO THROUGH THE FORM TO THE UPDATE PAGE-->
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $userid TOO THROUGH THE FORM TO THE UPDATE PAGE-->
                        <!-- Start: Username field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Username:</label>
                            <div class="col-sm-10 col-md-6">
                                <input class="form-control" type="text" name="username" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required">
                            </div>
                        </div>
                        <!-- End: Username field -->
                        <!-- Start: Password field -->
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Password:</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                                    <input class="form-control" type="password" name="newpassword" autocomplete="new-password" placeholder="Leave it blank if you don't want to change">
                                </div>
                            </div>
                            <!-- End: Password field -->
                            <!-- Start: Email field -->
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Email:</label>
                                <div class="col-sm-10 col-md-6">
                                    <input class="form-control" type="email" name="email" value="<?php echo $row['Email'] ?>" required="required">
                                </div>
                            </div>
                            <!-- End: Email field -->
                            <!-- Start: FullName field -->
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Full Name:</label>
                                <div class="col-sm-10 col-md-6">
                                    <input class="form-control" type="text" name="full" value="<?php echo $row['FullName'] ?>" required="required">
                                </div>
                            </div>
                            <!-- End: FullName field -->
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




        } elseif ($do == 'Update') { // This is for handling $do == 'Edit' HTML Form submission in the Edit Member Page    // Update Page (GET Request coming from the Form in Edit Page)
            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">'; // To show errors using Bootstrap (alert-danger class)

            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the URL is not copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)    // if the HTML Form is submitted with a POST method/verb HTTP Request
                // Getting the variables and their values coming from the Form in Edit Page (from name and value attributes in the <input> fields (Ex: <input class="form-control" type="text" name="full" value="<?php echo $row['FullName']">)
                // Storing the name attribute values of the <input> fields in New variables
                $id    = $_POST['userid']; // from the hidden HTML <input> field
                $user  = $_POST['username'];
                $email = $_POST['email'];
                $name  = $_POST['full'];
                // echo $id . $user . $email . $name;

                // Password Trick
                $pass = '';
                /* if (empty($_POST['newpassword'])) {
                    $pass = $_POST['oldpassword'];
                } else {//Password field not empty
                    $pass = sha1($_POST['newpassword']);
                } */
                // Using the Ternary Operator to execute the previous code
                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                // Form Validation
                $formErrors = array(); // Empty array

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>'; 
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>'; 
                }

                if (empty($user)) {
                    $formErrors[] = 'Username can\'t be <strong>empty</strong>'; 
                }

                if (empty($name)) {
                    $formErrors[] = 'Full Name can\'t be <strong>empty</strong>'; 
                }

                if (empty($email)) {
                    $formErrors[] = 'Email can\'t be <strong>empty</strong>'; 
                }

                // A loop over/through the array to echo the errors
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                // Checking if there are no errors, then proceeding to update operation
                if (empty($formErrors)) {
                    // The next query is to avoid updating a member name with another already existing member username
                    // This query means: For example, Show me if there is a member called Sameh and its ID is not this ID (if there is another one, this means u can't update to this name)
                    $stmt2 = $con->prepare('SELECT * FROM `users` WHERE `Username` = ? AND `UserID` != ?'); // Check if there're any other users with the same `Username` (`Username` column is UNIQUE) but with a different `UserID`
                    $stmt2->execute(array($user, $id));
                    $count = $stmt2->rowCount();

                    if ($count == 1) { // if there's another user with the same `Username` (`Username` column is UNIQUE) but with a different `UserID`, cancel the operation
                        $theMsg = '<div class="alert alert-danger">Sorry, This user already exists</div>';
                        redirectHome($theMsg, 'back');
                    } else { // if there're no any other users with the same `Username` (`Username` column is UNIQUE) and a different `UserID`, proceed to UPDATE
                        // Updating the user record corresponding to these info
                        $stmt = $con->prepare('UPDATE `users` SET `Username` = ? , `Email` = ? , `FullName` = ? , `Password` = ? WHERE `UserID` = ?');
                        $stmt->execute(array($user, $email, $name, $pass, $id));

                        // Echoing a Success Message
                        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Updated Successfully.</div><br>';
                        redirectHome($theMsg, 'back');
                    }
                }


            } else {

                $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar, you must come through a POST or GET HTTP Request.</div><br>';
                redirectHome($theMsg);
            }
            echo '</div>'; // The continer div





        } elseif ($do == 'Delete') { // GET request of the Delete Member Page (from Delete button in Members Page)
            echo '<h1 class="text-center">Delete Member</h1>';
            echo '<div class="container">';
                // Checking if userid GET Request is numeric only and getting its integer value
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; // Coming from the GET Request of the red Delete button <a> anchor HTML element in members.php page
                
                // Checking if the user exists in the database
                $check = checkItem('`UserID`', '`users`', $userid);
                
                // If there's such $userid, delete the user
                if ($check > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    // echo 'Good this is the form';
                    $stmt = $con->prepare('DELETE FROM `users` WHERE `UserID` = :zuser');
                    $stmt->bindParam(':zuser', $userid);
                    $stmt->execute();

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Deleted Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';




            
        } elseif ($do == 'Activate') { // coming from the GET request of the light blue Activate button <a> HTML element in the Manage members.php page    // UPDATE-ing the `RegStatus` column in `users` table from 0 to 1
            echo '<h1 class="text-center">Activate Member</h1>';
            echo '<div class="container">';
                // Checking if userid GET Request is numeric only and getting its integer value
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; // Coming from the light blue Activate button <a> anchor HTML element in members.php page
                
                // Checking if the user exists in the database
                $check = checkItem('`UserID`', '`users`', $userid);
                
                // If there's such $userid, Activate the user
                if ($check > 0) { // You can add this for more security to prevent Admin from changing id from the address bar and edit the user data from the address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    // echo 'Good this is the form';
                    $stmt = $con->prepare('UPDATE `users` SET `RegStatus` = 1 WHERE `UserID` = ?');
                    $stmt->execute(array($userid));

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Activated Successfully.</div><br>';
                    redirectHome($theMsg);
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';
        }





        include $tpl . 'footer.php';

    } else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\admin\index.php page    //This is for security to prevent anyone from copy/paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request
        // echo 'You Are Not Authorized to View This Page (from dashboard.php)<br>';
        header('Location: index.php');
        exit();
    }



    ob_end_flush(); // For fixing the "headers already sent" error