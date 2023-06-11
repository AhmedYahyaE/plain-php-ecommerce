<?php

// This is a PROTECTED ROUTE!

/* Categories Page */


ob_start(); // For fixing the "headers already sent" error (before session_start() function)



session_start();

$pageTitle = 'Categories'; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file

// Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\admin\index.php page
if (isset($_SESSION['Username'])) { // if there's a user stored in the Session, allow the user to access this page

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        



        if ($do == 'Manage') {
            $sort = 'ASC'; // The DEFAULT ordering is ASC (not DESC)
            $sort_array = array('ASC', 'DESC');

            // The type of sorting will be coming from the GET Request from the <div> with class "ordering" in the same page (the HTML <a> anchor link)
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM `categories` WHERE `parent` = 0 ORDER BY `Ordering` $sort"); // SELECT the main/parent/root categories (`parent` = 0) only (not the subcategories / child categories)
            $stmt2->execute();
            $cats = $stmt2->fetchAll();

            if (!empty($cats)) { // If there're parent/main/root categories (`parent` = 0)
    ?>
                <h1 class="text-center">Manage Categories</h1>
                <div class="container categories">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Manage Categories
                            <div class="option pull-right"> <!-- The .option CSS class will be used by jQuery in backend.js file -->
                                <i class="fa fa-sort" aria-hidden="true"></i> Ordering: [
                                <a class="<?php if ($sort == 'ASC')  {echo 'active';} ?>" href="?sort=ASC" >ASC</a> |  <!-- The .active CSS class is used to color the <a> HTML element using CSS. Check backend.css file -->
                                <a class="<?php if ($sort == 'DESC') {echo 'active';} ?>" href="?sort=DESC">DESC</a> ] <!-- The .active CSS class is used to color the <a> HTML element using CSS. Check backend.css file -->
                                <i class="fa fa-eye" aria-hidden="true"></i> View: [ <span class="active" data-view="full">Full</span> | <span>Classic</span> ] <!-- Custom HTML data-* Attribute (data-view="full") will be handled by jQuery in admin/layout/js/backend.js to switch Full/Classic views --> <!-- The .active CSS class is used to color the <span> HTML element using CSS. Check backend.css file -->
                            </div>
                        </div>
                        <div class="panel-body">
    <?php
                            // echo '<pre>', var_dump($cats), '</pre>';
                            // exit;

                            foreach ($cats as $cat) {
                                echo '<div class="cat">';
                                    echo '<div class="hidden-buttons">';
                                        echo '<a href="categories.php?do=Edit&catid='   . $cat['ID'] . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>';
                                        echo '<a href="categories.php?do=Delete&catid=' . $cat['ID'] . '" class="confirm btn btn-xs btn-danger"><i class="fa fa-times"    aria-hidden="true"></i> Delete</a>';
                                    echo '</div>';

                                    echo '<h3>' . $cat['Name'] . '</h3>';
                                    echo '<div class="full-view">'; //    .full-view    CSS class will be used by jQuery to switch Full/Classic views. Check backend.js
                                        echo '<p>'; if ($cat['Description'] == '') {echo 'This category has no Description';} else {echo $cat['Description'];} echo '</p>';

                                        // Display categories "Tags"
                                        if ($cat['Visibility'] == 1)    {echo '<span class="visibility cat-span"><i class="fa fa-eye"   aria-hidden="true"></i> Hidden</span>';}
                                        if ($cat['Allow_Comment'] == 1) {echo '<span class="commenting cat-span"><i class="fa fa-times" aria-hidden="true"></i> Comments Disabled</span>';}
                                        if ($cat['Allow_Ads'] == 1)     {echo '<span class="advertises cat-span"><i class="fa fa-times" aria-hidden="true"></i> Ads Disabled</span>';}
                                echo '</div>';

                                // echo "hi {$cat['ID']}";

                                // Getting Child Categories (subcategories)
                                $childCats = getAllFrom('*', '`categories`', '`ID`', "WHERE `parent` = {$cat['ID']}", '', 'ASC');

                                if (!empty($childCats)) {
                                    echo '<h4 class="child-head">Child Categories</h4>';
                                    echo '<ul class="list-unstyled child-cats">';
                                        foreach ($childCats as $c) { // from functions.php
                                            echo  '<li class="child-link">
                                                        <a href="categories.php?do=Edit&catid='   . $c['ID'] . '">' . $c['Name'] . '</a>
                                                        <a href="categories.php?do=Delete&catid=' . $c['ID'] . '" class="show-delete confirm"> Delete</a>
                                                    </li>';
                                        }
                                    echo '</ul>';
                                }
                                echo '</div>';
                                echo '<hr>';

                            } 
    ?>
                        </div>
                    </div>
                    <a href="categories.php?do=Add" class="btn btn-primary add-category"><i class="fa fa-plus" aria-hidden="true"></i> Add New Category</a>
                </div>
    <?php

            } else { // If there're no parent/main/root categories (`parent` = any number other than 0 zero)
                echo '<div class="container">';
                    echo '<div class="nice-message">There are no categories to show</div>';
                    echo '<a href="categories.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Category</a>';
                echo '</div>';
            }





        } elseif ($do == 'Add') { // GET HTTP Request coming from the +Add New Category Button in admin/categories.php page (URL like:    GET http://127.0.0.1/eCommerce/admin/categories.php?do=Add    )
    ?>
            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start: Name field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name:</label>
                        <div class="col-sm-10 col-md-6">
                            <input class="form-control" type="text" name="name" required="required" placeholder="Name of the category">
                        </div>
                    </div>
                    <!-- End: Name field -->
                    <!-- Start: Description field -->
                    <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description:</label>
                            <div class="col-sm-10 col-md-6">
                                <input class="form-control" type="text" name="description" placeholder="Describe the category">
                            </div>
                        </div>
                    <!-- End: Description field -->
                    <!-- Start: Ordering field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering:</label>
                        <div class="col-sm-10 col-md-6">
                            <input class="form-control" type="text" name="ordering" placeholder="Number to arrange the category">
                        </div>
                    </div>
                    <!-- End: Ordering field -->
                    <!-- Start: Category Type (`parent` in `categories` database table) -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?:</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option> <!--    `parent` = 0    , which means that the new category will be a Main/Parent/Root category -->
    <?php
                                $allCats = getAllFrom('*', '`categories`', '`ID`', 'WHERE `parent` = 0', '', 'ASC'); // Get the parent/main/root categories

                                // `parent` = any number other than 0    means that the new category will be a Child category / Subcategory
                                foreach ($allCats as $cat) { // Display/Show all the parent/main/root categories
                                    echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
                                }
    ?>
                            </select>
                        </div>
                    </div>
                    <!-- End: Category Type (`parent` in `categories` database table) -->
                    <!-- Start: Visibility field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visibility:</label>
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked><!--value = 0 means the category is visible (it's default as 0 in the categories table in the database, 1 means hidden-->
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1"><!--value = 1 means the category is hidden (it's default as 0 in the categories table in the database, 1 means hidden-->
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                        <!-- End: Visibility field -->
                        <!-- Start: Allow_Comment field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Commenting:</label>
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked><!--value = 0 means the category is visible (it's default as 0 in the categories table in the database, 1 means hidden-->
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1"><!--value = 1 means the category is hidden (it's default as 0 in the categories table in the database, 1 means hidden-->
                                <label for="com-no">No</label>
                            </div>
                        </div>
                        <!-- End: Allow_Comment field -->
                        <!-- Start: Allow_Ads field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads:</label>
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked><!--value = 0 means the category is visible (it's default as 0 in the categories table in the database, 1 means hidden-->
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"><!--value = 1 means the category is hidden (it's default as 0 in the categories table in the database, 1 means hidden-->
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                        <!-- End: Allow_Ads field -->
                        <!-- Start: Submit field -->
                    <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input class="btn btn-primary btn-lg" type="submit" value="Add Category">
                            </div>
                        </div>
                        <!-- End: Submit field -->
                </form>
            </div>
    <?php





        } elseif ($do == 'Insert') { // POST HTTP Request coming from the HTML Form submission in the Add New Category page (HTML Form submission in    admin/categories.php?do=Add    )    (URL like:    POST http://127.0.0.1/eCommerce/admin/categories.php?do=Insert    )

            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the URL is not copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)    // if the HTML Form is submitted with a POST method/verb HTTP Request
                echo '<h1 class="text-center">Insert Category</h1>';
                echo '<div class="container">'; // To show errors using Bootstrap (alert-danger class)
                // Getting the variables and their values coming from the Form in Edit Page (from name and value attributes in the <input> fields (Ex: <input class="form-control" type="text" name="full" value="<?php echo $row['FullName']">)
                // Storing the name attribute values of the <input> fields in New variables
                $name    = $_POST['name'];
                $desc    = $_POST['description']; // Very Important: sha1() function considers an empty string as a value and not empty and hence it hashes it... so when sha1() function is used with empty() function, empty() never returns true (i.e. it never finds it really empty)
                $parent  = $_POST['parent'];
                $order   = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads     = $_POST['ads'];
                
                // Checking if the category already exists in the database
                $check = checkItem('`Name`', '`categories`', $name);
                if ($check == 1) { // this means that the category already exists
                        $theMsg = '<div class="alert alert-danger">Sorry, This category already exists</div><br>';
                        redirectHome($theMsg, 'back');
                } else { // INSERT the newly created category
                    // Inserting Category info into database
                    $stmt = $con->prepare('INSERT INTO `categories` (`Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES (:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads)');
                    $stmt->execute(array(
                        'zname'    => $name,
                        'zdesc'    => $desc,
                        'zparent'  => $parent,
                        'zorder'   => $order,
                        'zvisible' => $visible,
                        'zcomment' => $comment,
                        'zads'     => $ads
                    ));

                    // Echoing a Success Message
                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Inserted Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                }

            } else { // If the coming HTTP Request is NOT with POST method (coming by copy/paste URL in the browser address bar (i.e. a GET request))
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar (i.e. the HTTP Request is GET method, not POST method), you must come through a POST or GET HTTP Request.</div><br>';
                redirectHome($theMsg, 'back');
                echo '</div>';
            }
            echo '</div>'; // The continer div





        } elseif ($do == 'Edit') { // GET Request is coming from the blue Edit button in Manage Categories Page (URL like:    GET http://127.0.0.1/eCommerce/admin/categories.php?do=Edit&catid=17    )
            // Checking if catid from the GET Request is numeric only and getting its integer value
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // Selecting all data depending on that $catid
            $stmt = $con->prepare('SELECT * FROM `categories` WHERE `ID` = ?'); // GroupID = 1 to make sure the user is an Admin

            // Executing the query
            $stmt->execute(array($catid));

            // Retrieving/Fetching data resulted from the query
            $cat = $stmt->fetch();

            // Get the row count
            $count = $stmt->rowCount(); // returns the number of rows affected by the last SQL statement (from the execute() function)

            // If there's such a $catid, show the HTML Form
            if ($count > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
    ?>
            <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <!--THIS IS A HIDDEN INPUT FIELD THAT ENABLES US TO SEND THE $catid TOO THROUGH THE HTML FORM TO THE UPDATE PAGE-->
                        <input type="hidden" name="catid" value="<?php echo $catid ?>">
                        <!-- Start: Name field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name:</label>
                            <div class="col-sm-10 col-md-6">
                                <input class="form-control" type="text" name="name" required="required" placeholder="Name of the category" value="<?php echo $cat['Name']; ?>">
                            </div>
                        </div>
                        <!-- End: Name field -->
                        <!-- Start: Description field -->
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Description:</label>
                                <div class="col-sm-10 col-md-6">
                                    <input class="form-control" type="text" name="description" placeholder="Describe the category" value="<?php echo $cat['Description']; ?>">
                                </div>
                            </div>
                        <!-- End: Description field -->
                        <!-- Start: Ordering field -->
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Ordering:</label>
                                <div class="col-sm-10 col-md-6">
                                    <input class="form-control" type="text" name="ordering" placeholder="Number to arrange the category" value="<?php echo $cat['Ordering']; ?>">
                                </div>
                            </div>
                            <!-- End: Ordering field -->
                            <!-- Start: Category Type (`parent` in `categories` database table) -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Parent?:</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="parent">
                                        <option value="0">None</option><!-- `parent` = 0 is none which means it's a Main/Parent/Root category-->
    <?php
                                        $allCats = getAllFrom('*', '`categories`', '`ID`', 'WHERE `parent` = 0', '', 'ASC');
                                        foreach ($allCats as $c) {
                                            echo '<option value="' . $c['ID'] . '"'; if ($cat['parent'] == $c['ID']) {echo ' selected';} echo '>' . $c['Name'] . '</option>';
                                        }
    ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End: Category Type (`parent` in `categories` database table) -->
                            <!-- Start: Visibility field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Visibility:</label>
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) {echo 'checked';} ?>><!--value = 0 means the category is visible (it's default as 0 in the categories table in the database, 1 means hidden-->
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {echo 'checked';} ?>><!--value = 1 means the category is hidden (it's default as 0 in the categories table in the database, 1 means hidden-->
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                            <!-- End: Visibility field -->
                            <!-- Start: Allow_Comment field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Commenting:</label>
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {echo 'checked';} ?>><!--value = 0 means the category is visible (it's default as 0 in the categories table in the database, 1 means hidden-->
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {echo 'checked';} ?>><!--value = 1 means the category is hidden (it's default as 0 in the categories table in the database, 1 means hidden-->
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                            <!-- End: Allow_Comment field -->
                            <!-- Start: Allow_Ads field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Ads:</label>
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {echo 'checked';} ?>><!--value = 0 means the category is visible (it's default as 0 in the categories table in the database, 1 means hidden-->
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {echo 'checked';} ?>><!--value = 1 means the category is hidden (it's default as 0 in the categories table in the database, 1 means hidden-->
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                            <!-- End: Allow_Ads field -->
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
                
                } else { // If there's no such $catid, show an Error Message
                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-danger"><strong>ERROR: There\'s no such ID</strong></div><br>';
                    redirectHome($theMsg);
                    echo '</div>';
                }




                
        } elseif ($do == 'Update') { // POST HTTP Request coming from the HTML Form submission in Edit Category (GET request sent inside from admin/categories?do=Edit&catid=19) (URL like:    POST http://127.0.0.1/eCommerce/admin/categories.php?do=Update&catid=19    )
            echo '<h1 class="text-center">Update Category</h1>';
            echo '<div class="container">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the URL is not copy and paste (to make sure that the HTTP Request is not a GET request (copy/paste in the browser address bar), but it's a POST request)     // if the HTML Form is submitted with a POST method/verb HTTP Request
                // Getting the variables and their values coming from the Form in Edit Page (from name and value attributes in the <input> fields (Ex: <input class="form-control" type="text" name="full" value="<?php echo $row['FullName']">)
                // Storing the name attribute values of the <input> fields in New variables
                $id      = $_POST['catid'];
                $name    = $_POST['name'];
                $desc    = $_POST['description'];
                $order   = $_POST['ordering'];
                $parent  = $_POST['parent'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads     = $_POST['ads'];

                // Updating the database corresponding to these info
                $stmt = $con->prepare('UPDATE `categories` SET `Name` = ? , `Description` = ? , `Ordering` = ? , `parent` = ?, `Visibility` = ? , `Allow_Comment` = ? , `Allow_Ads` = ? WHERE `ID` = ?');
                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));
                // Echoing a Success Message
                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Records Updated Successfully.</div><br>';
                redirectHome($theMsg, 'back');
                
            } else {

                $theMsg = '<div class="alert alert-danger">Sorry, You can\'t browse this page directly by copy paste in the address bar (i.e. the HTTP Request is GET method, not POST method), you must come through a POST or GET HTTP Request.</div><br>';
                redirectHome($theMsg);
            }
            echo '</div>'; // The continer div





        } elseif ($do == 'Delete') { // The GET HTTP Request is coming from the Delete Button in categories.php
            echo '<h1 class="text-center">Delete Category</h1>';
            echo '<div class="container">';
                // Checking if catid GET Request is numeric only and getting its integer value
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
                
                // Checking if the category exists in the database
                $check = checkItem('`ID`', '`categories`', $catid);
                
                // If there's such category id, proceed to delete
                if ($check > 0) { // You can add this for more security to prevent Admin to change id from address bar and edit the user data from address bar:  if ($count > 0 && $_SESSION['Username'] == $row['Username']) {
                    // echo $row['UserID'] . ' ' . $row['Username'] . ' ' . $row['Password'] . ' ' . $row['FullName'] . ' ' . $row['Email'] . '<br>';
                    // echo 'Good this is the form';
                    $stmt = $con->prepare('DELETE FROM `categories` WHERE `ID` = :zid');
                    $stmt->bindParam(':zid', $catid);
                    $stmt->execute();

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted Successfully.</div><br>';
                    redirectHome($theMsg, 'back');
                } else {
                    $theMsg ='<div class="alert alert-danger">This ID does Not exist (from Delete Page)</div><br>';
                    redirectHome($theMsg);
                }
            echo '</div>';
        }



        // Include the footer.php
        include $tpl . 'footer.php';



} else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\admin\index.php page
    header('Location: index.php');
    exit();
}



ob_end_flush(); // For fixing the "headers already sent" error