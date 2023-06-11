<?php
// This is a PROTECTED ROUTE!

session_start();

$pageTitle = 'Create New Item'; // Check eCommerce/includes/templates/header.php file    AND    eCommerce/includes/functions/functions.php file

include 'init.php';


// Protected Routes (Protecting Routes): Note This page is a Protected Route! We protect this route by checking for if there's a user stored in the Session. If there's a user stored in the Session, allow the user to access this page, and if not, redirect the website guest/visitor to the eCommerce\login.php page
if (isset($_SESSION['user'])) { // if there's a user stored in the Session, allow the user to access this page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // to make sure the user is not coming by URL copy/paste i.e. not coming from a GET Request    // if the HTML Form is submitted with a POST method/verb HTTP Request

            // Note: We implemented BOTH client-side validation and server-side validation

            // HTML Form Server-side Validation
            $formErrors = array(); // to print Validation Errors in the errors div down below in this page

            // $name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
            $name = htmlspecialchars($_POST['name']); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)

            // $desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
            $desc = htmlspecialchars($_POST['description']); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)

            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            // $country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
            $country = htmlspecialchars($_POST['country']); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)

            $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            // $tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
            $tags = htmlspecialchars($_POST['tags']); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)


            if (strlen($name) < 4) {
                $formErrors[] = 'Item Title must be at least 4 Characters';
            }

            if (strlen($desc) < 10) {
                $formErrors[] = 'Item Description must be at least 10 Characters';
            }

            if (strlen($country) < 2) {
                $formErrors[] = 'Item Country must be at least 2 Characters';
            }

            if (empty($price)) {
                $formErrors[] = 'Item Price must be not Empty';
            }

            if (empty($status)) {
                $formErrors[] = 'Item Status must be not Empty';
            }

            if (empty($category)) {
                $formErrors[] = 'Item Category must be not Empty';
            }

            // Checking if there are no errors, then proceeding to add/INSERT operation
            if (empty($formErrors)) { // means if there are no errors, then it is OKAY
                //Inserting User info into database
                $stmt = $con->prepare('INSERT INTO `items` (`Name`, `Description`, `Price`, `Country_Made`, `Status`, `Add_Date`, `Cat_ID`, `Member_ID`, `tags`) VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)');
                //echo '<pre>', print_r($stmt), '</pre><br>';

                $stmt->execute(array(
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zprice'   => $price,
                    'zcountry' => $country,
                    'zstatus'  => $status,
                    'zcat'     => $category,
                    'zmember'  => $_SESSION['uid'],
                    'ztags'    => $tags
                ));

                // Echoing a Success Message
                if ($stmt) {
                    $succesMsg = 'Item Added';
                }
            }
        }
    ?>

        <h1 class="text-center"><?php echo $pageTitle ?></h1> <!-- Check /includes/templates/header.php and /includes/functions/functions.php -->
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $pageTitle ?></div> <!-- Check /includes/templates/header.php and /includes/functions/functions.php -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">

                                <!-- HTML Form Client-side Validation -->
                                <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <!-- Start: Name field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Name:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input pattern=".{4,}" title="This field requires at least 4 characters" required class="form-control live" data-class=".live-title" type="text" name="name" required="required" placeholder="Name of the item">
                                        </div>
                                    </div>
                                    <!-- End: Name field -->
                                    <!-- Start: Description field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Description:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input pattern=".{10,}" title="This field requires at least 10 characters" required class="form-control live" data-class=".live-desc" type="text" name="description" required="required" placeholder="Description of the item">
                                        </div>
                                    </div>
                                    <!-- End: Description field -->
                                    <!-- Start: Price field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Price:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control live" data-class=".live-price" type="text" name="price" required="required" placeholder="Price of the item">
                                        </div>
                                    </div>
                                    <!-- End: Price field -->
                                    <!-- Start: Country field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Country:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="text" name="country" required="required" placeholder="Country of Made">
                                        </div>
                                    </div>
                                    <!-- End: Country field -->
                                    <!-- Start: Status field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Status:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="status" required>
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
                                        <label class="col-sm-3 control-label">Category:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="category" required>
                                                <option value="0">...</option>
    <?php
                                            $cats = getAllFrom('*', '`categories`', '`ID`', '', '');
                                            foreach ($cats as $cat) {
                                                echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
                                            }
    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End: Categories field -->
                                    <!-- Start: Tags field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Tags:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="text" name="tags" placeholder="Separate tags with commas (,)">
                                        </div>
                                    </div>
                                    <!-- End: Tags field -->
                                    <!-- Start: Submit field -->
                                    <div class="form-group form-group-lg">
                                            <div class="col-sm-offset-3 col-sm-9">
                                            <input class="btn btn-primary btn-sm" type="submit" value="Add Item">
                                            </div>
                                    </div>
                                    <!-- End: Submit field -->
                                    </form>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">
                                        $<span class="live-price">0</span>
                                    </span><!--will be used by jQuery-->
                                    <img class="img-responsive" src="img.png" alt="random image">
                                    <div class="caption">
                                        <h3 class="live-title">Title</h3><!--will be used by jQuery-->
                                        <p class="live-desc">Description</p><!--will be used by jQuery-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Looping(printing) through form errors-->
    <?php
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    if (isset($succesMsg)) {
                        echo '<div class="alert alert-success">' . $succesMsg . '</div>';
                    }
    ?>
                        <!--Looping(printing) through form errors-->                       
                    </div>
                </div>
            </div>
        </div>


    <?php

} else { // Protected Routes (Protecting Routes): if there's no user stored in the Session, redirect the website guest/visitor to the eCommerce\login.php page
    header('Location: login.php');
    exit();
}



include $tpl . 'footer.php'; 
?>