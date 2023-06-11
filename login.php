<?php

session_start();
// echo "<pre>MY SESSION ARRAY ELEMENTS ARE: \n", print_r($_SESSION), '(from the FrontEnd login.php)</pre>';


$pageTitle = 'Login/Signup'; // Check eCommerce/includes/templates/header.php file    AND    eCommerce/includes/functions/functions.php file


// If there's a user registerd in the Session, redirect the user to eCommerce\index.php
if (isset($_SESSION['user'])) { // VERY IMPORTANT: SESSION OF NORMAL USERS MUST BE DIFFERENT THAN ADMIN'S SESSION
    header('Location: index.php');
} // Session will be planted at the end of this page before the Markup (HTML)



include 'init.php';



// Checking if the user is coming through a POST HTTP Request and not from copy/paste of the URL i.e. a GET Request (coming from the form at the end of this page)
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // There are TWO possibilities: Coming from the Login HTML Form Button Or Signup HTML Form Button    // if the HTML Form is submitted with a POST method/verb HTTP Request


    if (isset($_POST['login'])) { // coming from the "Login" HTML Form Button (not from the "Signup" HTML Form button) -- Note that we gave the button <input> itself an HTML 'name' attribute (    name="login"    and the other    name="signup"    )

        // Validation
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass); // We check the hashed password, not the actual password    // to store the passwords in the database in the hashed state for security to prevent them from being stolen
        // echo $user . ' ' . $pass;

        // Checking if the user trying to login (Normal Users only (not Admins)) exists in the database
        $stmt = $con->prepare('SELECT `UserID`, `Username` , `Password` FROM `users` WHERE `Username` = ? AND `Password` = ?'); // Using PDO Named Parameters / Named Placeholders
        $stmt->execute(array(
            $user, $hashedPass
        ));

        $get = $stmt->fetch();

        $count = $stmt->rowCount(); // if 0, this means there is no user registered with that login data. If 1, the user exists
        // echo '<br>My count is ' . $count . '<br>';

        if ($count > 0) { // if the user trying to login exists in our database `users` table, plant the user in the Session (create the user's Session)
            // Plant the Session of the user logging in
            $_SESSION['user'] = $user; // Registering Username
            $_SESSION['uid']  = $get['UserID']; // Registering UserID
            // echo "<pre>MY SESSION ARRAY ELEMENTS ARE: \n", print_r($_SESSION), '(from FrontEnd login.php)</pre>';


            header('Location: index.php'); // redirect to eCommerce/index.php
            exit();
        } 

    } else { // coming from the "Signup" HTML Form Button (not from the "Login" HTML Form button) -- Note that we gave the button <input> itself an HTML 'name' attribute (    name="signup"    and the other    name="login"    )
        // echo $_POST['username'];

        // Errors array for Validation
        $formErrors = array(); // to print the Validation Errors in the errors div down below in this page

        $username  = $_POST['username'];
        $password  = $_POST['password']; // Password
        $password2 = $_POST['password2']; // Password confirmation
        $email     = $_POST['email'];


        // Validation
        if (isset($username)) {
            // $filteredUser = filter_var($username, FILTER_SANITIZE_STRING); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
            $filteredUser = htmlspecialchars($username); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)

            if (strlen($filteredUser) < 4) {
                $formErrors[] = 'Username must be more than 3 characters';
            }
        }

        // Password validation contains TWO errors
        if (isset($password) && isset($password2)) {
            if (empty($password)) { // to gurantee/make sure that user must enter a password and don't leave the password field empty
                $formErrors[] = 'Password can\'t be empty';
            }

            // Password Confirmation
            if(sha1($password) !== sha1($password2)) {
                $formErrors[] = 'Passwords don\'t match each other';
            }
        }

        if (isset($email)) {
            $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'This email is not valid';
            }
        }

        // Checking if there are no errors, then proceed to Signup (adding) the user to database
        if (empty($formErrors)) {
            // Checking if the user already exists in the database `users` table
            $check = checkItem('`Username`', '`users`', $username);

            if ($check == 1) { // this means that the user already exists
                    $formErrors[] = 'This user already exists';
            } else { // go on / proceed
                // Inserting User info into database
                $stmt = $con->prepare('INSERT INTO `users` (`Username`, `Password`, `Email`, `RegStatus`, `Date`) VALUES (:zuser, :zpass, :zmail, 0, now())');
                $stmt->execute(array(
                    'zuser' => $username,
                    'zpass' => sha1($password), // the hash-ed form of the password
                    'zmail' => $email
                ));

                //Echoing a Success Message
                $succesMsg = 'Congratz, You Are Now A Registered User'; // will be echo-ed down below in this file
            }
        }
    }

    
}

?>

    <div class="container login-page"> <!-- Login/Signup page -->

        <!-- Note: We'll switch Login form or Signup form using jQuery. Check eCommerce\layout\js\front.js -->
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1> <!-- The CSS Class "selected" and the Custom HTML data-* Attribute are used by jQuery to switch between showing Login and Signup HTML Forms -->

        <!-- Note: We'll switch Login form or Signup form using jQuery. Check eCommerce\layout\js\front.js -->



        <!--Start: Login Form-->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- class="login" will be used by jQuery to show the relevant HTML Form (to switch between the Login / SignUp HTML Forms). Check layout/js/front.js -->
            <!--Input divs are used for jQuery astrisk CSS-->
            <div class="input-container"><input class="form-control" type="text"     name="username" autocomplete="off"          placeholder="Type Your Username"      required></div>
            <div class="input-container"><input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type A Complex Password" required></div>
            <input class="btn btn-primary btn-block" type="submit" name="login" value="Login"> <!--We gave the button <input> itself an HTML name attribute    name="login"    to distinguish it from other HTML forms buttons to be used up there in this file to decide whether the user is comming from a Login or a Signup HTML Form using the $_POST superglobal -->
        </form>
        <!--End: Login Form-->



        <!--Start: Signup Form-->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- class="signup" will be used by jQuery to show the relevant HTML Form (to switch between the Login / SignUp HTML Forms). Check layout/js/front.js -->
            <!--Input divs are used for jQuery astrisk CSS-->
            <!--HTML5 Validation-->
            <div class="input-container"><input pattern=".{4,}" title="Username must be more than 3 characters" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required></div>
            <div class="input-container"><input minlength="6" class="form-control" type="password" name="password"  autocomplete="new-password" placeholder="Type Your Password"       required></div>
            <div class="input-container"><input minlength="6" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type Your Password Again" required></div>
            <div class="input-container"><input               class="form-control" type="email"    name="email"                                 placeholder="Type A Valid Email"       required></div>
            <input class="btn btn-success btn-block" type="submit" name="signup" value="Signup"> <!--We gave the button <input> itself an HTML name attribute    name="signup"    to distinguish it from other HTML forms buttons to be used up there in this file to decide whether the user is comming from a Login or a Signup HTML Form using the $_POST superglobal -->
        </form>
        <!--End: Signup Form-->



        <!--Start: A div for Showing errors-->
        <div class="the-errors text-center">
<?php
            if (!empty($formErrors)) { // If there are errors, show them
                foreach ($formErrors as $error) {
                    echo '<div class="msg error">' . $error . '</div>';
                }
            }

            if (isset($succesMsg)) {
                echo '<div class="msg success">' . $succesMsg . '</div>';
            }
?>
        </div>
        <!--End: A div for Showing errors-->


    </div>


<?php



// Footer
include $tpl . 'footer.php';






