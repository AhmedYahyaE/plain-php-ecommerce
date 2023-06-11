<?php
// This is a PROTECTED ROUTE!

/* Items PAGE */


ob_start(); // For fixing the "headers already sent" error (before session_start() function)



session_start();

$pageTitle = 'Items'; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file

// Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\admin\index.php page
if (isset($_SESSION['Username'])) { // if there's a user stored in the Session, allow the user to access this page    // means if there is a session active (user is already logged in), don't show him the form login page and redirect him to dashboard.php
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';




    if ($do == 'Manage') { // GET HTTP Request coming from the normal Items link in the navbar (e.g.    http://127.0.0.1/eCommerce/admin/items.php    )
        $stmt = $con->prepare('SELECT `items`.*, `categories`.`Name` AS My_Category_Name, `users`.`Username` AS My_Username FROM `items`
            INNER JOIN `categories` ON `categories`.`ID` = `items`.`Cat_ID`
            INNER JOIN `users`      ON `users`.`UserID`  = `items`.`Member_ID`
            ORDER BY `Item_ID` DESC
        ');

        // Executing the statement
        $stmt->execute();

        // Retrieving/Fetching all the rows and assigning them to a variable
        $items = $stmt->fetchAll();
        // echo '<pre>', var_dump($rows), '</pre>';
        // echo '<pre>', print_r($rows), '</pre>';

        if (!empty($items)) {
?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>
<?php
                    foreach ($items as $item) {
                        echo '<tr>';
                            echo '<td>' . $item['Item_ID']   . '</td>';
                            echo '<td>' . $item['Name'] . '</td>';
                            echo '<td>' . $item['Description']    . '</td>';
                            echo '<td>' . $item['Price'] . '</td>';
                            echo '<td>' . $item['Add_Date'] . '</td>';
                            echo '<td>' . $item['My_Category_Name'] . '</td>'; // from the last query
                            echo '<td>' . $item['My_Username'] . '</td>'; // from the last query
                            echo '<td>
                                        <a href="items.php?do=Edit&itemid='   . $item['Item_ID'] . '"class="btn btn-block btn-success       "><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>   <!-- Used the btn-block CSS class to solve the design problem of the Approve Button not being aligned with the Edit and Delete buttons on the same line (takeing a line break/newline) -->
                                        <a href="items.php?do=Delete&itemid=' . $item['Item_ID'] . '"class="btn btn-block btn-danger confirm"><i class="fa fa-times"           aria-hidden="true"></i> Delete</a> <!-- Used the btn-block CSS class to solve the design problem of the Approve Button not being aligned with the Edit and Delete buttons on the same line (takeing a line break/newline) --> ';
                                        if ($item['Approve'] == 0) { // means that the user request hasn't been appproved yet, so show the Approve Button to Admin to make Approve = 1
                                            echo '
                                                <a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '"class="btn btn-block btn-info activate"><i class="fa fa-check" aria-hidden="true"></i> Approve</a> <!-- Creating a line break/newline after the echo to be just like the previous two buttons (Edit and Delete Buttons) having line breaks/newlines between them, to avoid that the Approve Button becoming closely adhered to the Delete Button (no margin between them!). I used "View Page Source" to solve this problem arising because of using the if condition with the Approve Button, which leads to creating the Activate Button <a> HTML element without a line break/newline between it and its preceding Delete Button HTML element! --> <!-- Used the btn-block CSS class to solve the design problem of the Approve Button not being aligned with the Edit and Delete buttons on the same line (takeing a line break/newline) -->
                                            ';
                                        }
                            echo '</td>';
                        echo '</tr>';
                    }
?>

                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Item</a>
        </div>
<?php
        } else {
            echo '<div class="container">';
                echo '<div class="nice-message">There are no items to show</div>';
                echo '<a href="items.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Item</a>';
            echo '</div>';
        }
?>





<?php
    } elseif ($do == 'Add') {
?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start: Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="name" required="required" placeholder="Name of the item">
                    </div>
                </div>
                <!-- End: Name field -->
                <!-- Start: Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="description" required="required" placeholder="Description of the item">
                    </div>
                </div>
                <!-- End: Description field -->
                <!-- Start: Price field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="price" required="required" placeholder="Price of the item">
                    </div>
                </div>
                <!-- End: Price field -->
                <!-- Start: Country field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="country" required="required" placeholder="Country of Made">
                    </div>
                </div>
                <!-- End: Country field -->
                <!-- Start: Status field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status:</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End: Status field -->
                <!-- Start: Categories field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category:</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
<?php
                            $allCats = getAllFrom('*', '`categories`', '`ID`', 'WHERE `parent` = 0', '');
                            foreach ($allCats as $cat) {
                                echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
                                $childCats = getAllFrom('*', '`categories`', '`ID`', "WHERE `parent` = {$cat['ID']}", ''); // child categories = subcategories
                                foreach ($childCats as $child) {
                                    echo '<option value="' . $child['ID'] . '">---- ' . $child['Name'] . '</option>';
                                }
                            }
?>
                        </select>
                    </div>
                </div>
                <!-- End: Categories field -->
                <!-- Start: Members field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member:</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
<?php
                            $allMembers = getAllFrom('*', '`users`', '`UserID`', '', '');
                            foreach ($allMembers as $user) {
                                echo '<option value="' . $user['UserID'] . '">' . $user['Username'] . '</option>';
                            }
?>
                        </select>
                    </div>
                </div>
                <!-- End: Members field -->
                <!-- Start: Tags field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="tags" placeholder="Separate tags with commas (,)">
                    </div>
                </div>
                <!-- End: Tags field -->
                <!-- Start: Submit field -->
                <div class="form-group form-group-lg">
                     <div class="col-sm-offset-2 col-sm-10">
                        <input class="btn btn-primary btn-sm" type="submit" value="Add Item">
                     </div>
                </div>
                <!-- End: Submit field -->
            </form>






<?php
    } elseif ($do == 'Insert') { // POST HTTP Request coming from the HTML Form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the URL is not copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)    // if the HTML Form is submitted with a POST method/verb HTTP Request
            echo '<h1 class="text-center">Insert Item</h1>';
            echo '<div class="container">'; // To show errors using Bootstrap (alert-danger class)
            // Getting the variables and their values coming from the Form in Add Item Page (from name and value attributes in the <input> fields
            // Storing the name attribute values of the <input> fields of the Add New Item Page in New variables
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];
            $country = $_POST['country'];
            $status  = $_POST['status'];
            $member  = $_POST['member'];
            $cat     = $_POST['category'];
            $tags    = $_POST['tags'];


            // Form Validation
            $formErrors = array(); // Empty array

            if (empty($name)) {
                $formErrors[] = 'Name can\'t be <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Description can\'t be <strong>Empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price can\'t be <strong>Empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'Country can\'t be <strong>Empty</strong>';
            }
            if ($status == 0) {
                $formErrors[] = 'You must choose a <strong>Status</strong>';
            }
            if ($member == 0) {
                $formErrors[] = 'You must choose a <strong>Member</strong>';
            }
            if ($cat == 0) {
                $formErrors[] = 'You must choose a <strong>Category</strong>';
            }
    
            // A loop over/through the array to echo the errors
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Checking if there are no errors, then proceeding to INSERT operation
            if (empty($formErrors)) { // means there are no errors, then it is OKAY
                // Inserting User info into database
                $stmt = $con->prepare('INSERT INTO `items` (`Name`, `Description`, `Price`, `Country_Made`, `Status`, `Add_Date`, `Cat_ID`, `Member_ID`, `tags`) VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)');
                // echo '<pre>', print_r($stmt), '</pre><br>';

                $stmt->execute(array(
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zprice'   => $price,
                    'zcountry' => $country,
                    'zstatus'  => $status,
                    'zcat'     => $cat,
                    'zmember'  => $member,
                    ':ztags'   => $tags));

                // Echoing a Success Message
                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Inserted Successfully.</div><br>';
                redirectHome($theMsg, 'back');
            }

        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar, you must come through a POST or GET HTTP Request.</div><br>';
            redirectHome($theMsg);
            echo '</div>';
        }
        echo '</div>'; // The .continer <div>





    } elseif ($do == 'Edit') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
        //Selecting all data depending on that $userid
        $stmt = $con->prepare('SELECT * FROM `items` WHERE `Item_ID` = ?');
        // echo '<pre>', print_r($stmt), '</pre>';

        // Executing the query
        $stmt->execute(array($itemid));

        // Retrieving/Fetching data resulted from the query
        $item = $stmt->fetch();
        // var_dump($stmt->execute(array($username, $hashedPass)));

        // Get the row count
        $count = $stmt->rowCount(); // returns the number of rows affected by the last SQL statement(from execute())

        // If there's such $userid, show the form
        if ($count > 0) { //You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
            // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
            // echo 'Good this is the form';
?>
            <h1 class="text-center">Edit Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $itemid TOO THROUGH THE FORM TO THE UPDATE PAGE-->
                <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $itemid TOO THROUGH THE FORM TO THE UPDATE PAGE-->
                <!-- Start: Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="name" required="required" placeholder="Name of the item" value="<?php echo $item['Name'] ?>">
                    </div>
                </div>
                <!-- End: Name field -->
                <!-- Start: Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="description" required="required" placeholder="Description of the item" value="<?php echo $item['Description'] ?>">
                    </div>
                </div>
                <!-- End: Description field -->
                <!-- Start: Price field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="price" required="required" placeholder="Price of the item" value="<?php echo $item['Price'] ?>">
                    </div>
                </div>
                <!-- End: Price field -->
                <!-- Start: Country field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="country" required="required" placeholder="Country of Made" value="<?php echo $item['Country_Made'] ?>">
                    </div>
                </div>
                <!-- End: Country field -->
                <!-- Start: Status field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status:</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="1" <?php if ($item['Status'] == 1) {echo 'Selected';} ?>>New</option>
                            <option value="2" <?php if ($item['Status'] == 2) {echo 'Selected';} ?>>Like New</option>
                            <option value="3" <?php if ($item['Status'] == 3) {echo 'Selected';} ?>>Used</option>
                            <option value="4" <?php if ($item['Status'] == 4) {echo 'Selected';} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End: Status field -->
                <!-- Start: Categories field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category:</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
<?php
                            $stmt2 = $con->prepare('SELECT * FROM `categories`');
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach ($cats as $cat) {
                                echo '<option value="' . $cat['ID'] . '"'; if ($item['Cat_ID'] == $cat['ID']) {echo 'Selected';} echo '>' . $cat['Name'] . '</option>';
                            }
?>
                        </select>
                    </div>
                </div>
                <!-- End: Categories field -->
                <!-- Start: Members field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member:</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
<?php
                            $stmt = $con->prepare('SELECT * FROM `users`');
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user) {
                                echo '<option value="' . $user['UserID'] . '"'; if ($item['Member_ID'] == $user['UserID']) {echo 'Selected';} echo '>' . $user['Username'] . '</option>';
                            }
?>
                        </select>
                    </div>
                </div>
                <!-- End: Members field -->
                <!-- Start: Tags field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags:</label>
                    <div class="col-sm-10 col-md-6">
                        <input class="form-control" type="text" name="tags" placeholder="Separate tags with commas (,)" value="<?php echo $item['tags'] ?>">
                    </div>
                </div>
                <!-- End: Tags field -->
                <!-- Start: Submit field -->
                <div class="form-group form-group-lg">
                     <div class="col-sm-offset-2 col-sm-10">
                        <input class="btn btn-primary btn-sm" type="submit" value="Save Item">
                     </div>
                </div>
                <!-- End: Submit field -->
            </form>
<?php
            $stmt = $con->prepare('SELECT `comments`.*, `users`.`Username` AS My_user_name FROM `comments`
                                    INNER JOIN `users` ON `users`.`UserID`  = `comments`.`user_id`
                                    WHERE `item_id` =?
            ');

            // Executing the statement
            $stmt->execute(array($itemid));

            // Retrieving/Fetching all the rows and assigning them to a variable
            $rows = $stmt->fetchAll();
            // echo '<pre>', var_dump($rows), '</pre>';
            // echo '<pre>', print_r($rows), '</pre>';

            if (!empty($rows)) {
?>
            <h1 class="text-center">Manage [<?php echo $item['Name'] ?>] Comments</h1>
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>Comment</td>
                            <td>Username</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
<?php
                        foreach ($rows as $row) {
                            echo '<tr>';
                                echo '<td>' . $row['comment'] . '</td>';
                                echo '<td>' . $row['My_user_name'] . '</td>';   // from the last SQL query (AS ....)
                                echo '<td>' . $row['comment_date'] . '</td>';
                                echo '<td>
                                            <a href="comments.php?do=Edit&comid='   . $row['c_id'] . '"class="btn btn-success       "><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                            <a href="comments.php?do=Delete&comid=' . $row['c_id'] . '"class="btn btn-danger confirm"><i class="fa fa-times"           aria-hidden="true"></i> Delete</a>';
                                            if ($row['status'] == 0) { // means that if the user request hasn't been appproved yet, show the Activate button to Admin to make `RegStatus` = 1
                                                echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '"class="btn btn-info activate"><i class="fa fa-check" aria-hidden="true"></i> Approve</a>';
                                            }
                                echo '</td>';
                            echo '</tr>';
                        }
?>

                    </table>
                </div>
<?php           
                } 
?>
            </div>
        </div>
<?php       
        // If there's no such $userid, show Error Message
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger"><strong>ERROR: There\'s no such ID</strong></div><br>';
            redirectHome($theMsg);
            echo '</div>';
        }





    } elseif ($do == 'Update') { // POST HTTP Request coming from the HTML Form submission
        echo '<h1 class="text-center">Update Item</h1>';
            echo '<div class="container">'; // To show errors using Bootstrap (alert-danger class)
            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the HTTP request is not coming by copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)    // if the HTML Form is submitted with a POST method/verb HTTP Request
                // Storing the name attribute values of the <input> fields in New variables
                $id      = $_POST['itemid']; // from the hidden input field
                $name    = $_POST['name'];
                $desc    = $_POST['description'];
                $price   = $_POST['price'];
                $country = $_POST['country'];
                $status  = $_POST['status'];
                $member  = $_POST['member'];
                $cat     = $_POST['category'];
                $tags     = $_POST['tags'];
                // echo $id . $user . $email . $name;

                // Form Validation
                $formErrors = array(); // Empty array

                if (empty($name)) {
                    $formErrors[] = 'Name can\'t be <strong>Empty</strong>';
                }

                if (empty($desc)) {
                    $formErrors[] = 'Description can\'t be <strong>Empty</strong>';
                }

                if (empty($price)) {
                    $formErrors[] = 'Price can\'t be <strong>Empty</strong>';
                }

                if (empty($country)) {
                    $formErrors[] = 'Country can\'t be <strong>Empty</strong>';
                }

                if ($status == 0) {
                    $formErrors[] = 'You must choose a <strong>Status</strong>';
                }

                if ($member == 0) {
                    $formErrors[] = 'You must choose a <strong>Member</strong>';
                }

                if ($cat == 0) {
                    $formErrors[] = 'You must choose a <strong>Category</strong>';
                }

                // A loop over/through the array to echo the errors
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                // Checking if there are no errors, then proceeding to update operation
                if (empty($formErrors)) {
                    // Updating the database corresponding to these info
                    $stmt = $con->prepare('UPDATE `items` SET `Name` = ?, `Description` = ?, `Price` = ? , `Country_Made` = ?, `Status` = ?, `Cat_ID` = ?, `Member_ID` = ?, `tags` = ? WHERE `Item_ID` = ?');
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                    // Echoing a Success Message
                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Updated Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                }

            } else {
                $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar, you must come through a POST or GET HTTP Request.</div><br>';
                redirectHome($theMsg);
            }
            echo '</div>'; // The .continer <div>




            
    } elseif ($do == 'Delete') { // from the GET Request of the delete button in items.php
        echo '<h1 class="text-center">Delete Item</h1>';
            echo '<div class="container">';
                // Checking if userid GET Request is numeric only and getting its integer value
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                
                // Checking if the user exists in the database
                $check = checkItem('`Item_ID`', '`items`', $itemid);
                
                // If there's such $userid, show the form
                if ($check > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    // echo 'Good this is the form';
                    $stmt = $con->prepare('DELETE FROM `items` WHERE `Item_ID` = :zid');
                    $stmt->bindParam(':zid', $itemid);
                    $stmt->execute();

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';





    } elseif ($do == 'Approve') { // GET HTTP Request coming from the light blue Approve Button in items.php
        echo '<h1 class="text-center">Approve Item</h1>';
            echo '<div class="container">';
                // Checking if itemid GET Request is numeric only and getting its integer value
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                
                // Checking if the item exists in the database
                $check = checkItem('`Item_ID`', '`items`', $itemid);
                
                // If there's such a $userid, show the form
                if ($check > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    // echo 'Good this is the form';
                    $stmt = $con->prepare('UPDATE `items` SET `Approve` = 1 WHERE `Item_ID` = ?');
                    $stmt->execute(array($itemid));

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Approved Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';
    }



    // Include the footer.php
    include $tpl . 'footer.php';



} else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\admin\index.php page    // This is for security to prevent anyone from copy/paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request    // This is for security to prevent anyone from copy paste the page URL directly in the browser address bar (i.e. the HTTP Request is GET method, not POST method), and to make sure the user is coming through a POST HTTP request
    header('Location: index.php');
    exit();
}



ob_end_flush(); // For fixing the "headers already sent" error