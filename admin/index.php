<?php
// The Admin Panel Login

session_start();


$noNavbar = ''; // to prevent adding navbar.php to the page (Refer to init.php and adding navbar.php adding with the if condition)
$pageTitle = 'Login'; // Check eCommerce\admin\includes\templates\header.php file    AND    eCommerce\admin\includes\functions\functions.php file


// If the current user is authenticated/logged-in, redirect them directly to the eCommerce\admin\dashboard.php page
if (isset($_SESSION['Username'])) { // means if there is a session active (user is already logged-in), don't show them the login HTML form page and redirect him to dashboard.php
    header('Location: dashboard.php'); // Redirection to dashboard.php page
} // Session will be planted at the end of this page before the Markup (HTML)



include 'init.php';
// echo "<pre>MY SESSION ARRAY ELEMENTS ARE: \n", print_r($_SESSION), '(from dashboard.php)</pre>';



// If the HTML Form is submitted (the <button> is clicked)    // Checking if the user is coming through an HTTP POST Request and not from copy paste of the URL (coming from the form at the end of this page)
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // if the HTML Form is submitted with a POST method/verb HTTP Request
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password); // Hashing the passwords for security (to protect passwords from being stolen if a hacker could hack the database)

    // Checking if the user (ADMIN only) exists in the database
    $stmt = $con->prepare('SELECT `UserID`, `Username`, `Password` FROM `users` WHERE `Username` = ? AND `Password` = ? AND `GroupID` = 1 LIMIT 1'); // `GroupID` = 1 to make sure the user is an ADMIN only
    // echo '<pre>', print_r($stmt), '</pre>';

    $stmt->execute(array(
        $username, $hashedPass
    ));

    $row = $stmt->fetch();// to print the $row['UserID'] for the session below
    // var_dump($stmt->execute(array($username, $hashedPass)));

    $count = $stmt->rowCount(); // returns the number of rows affected by the last SQL statement(from execute())

    // If $count > 0, this means that database contains a record about that Username who tries to login (there's an admin with the said credentials), create their Session and allow them to login
    if ($count > 0) {
        // echo '<pre>', print_r($row), '</pre>'; // Or echo '<pre>', var_dump($row), '</pre>';
        // echo $row['UserID'];
        // exit;

        // Create the user's (admin's) Session
        $_SESSION['Username'] = $username; // Registering session name (setting the session variable)
        $_SESSION['ID']       = $row['UserID']; // Registering the Session ID as the user's (admin's) ID in the `users` database table


        // Redirect the user to eCommerce\admin\dashboard.php
        header('Location: dashboard.php'); // Redirection to dashboard.php page after logging in / login
        exit();
    }
}

?>
        
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><!--Form will submit data to itself and not to another page-->
            <h4 class="text-center">Admin Login</h4>
            <input class="form-control" type="text"     name="user" placeholder="Username" autocomplete="off">
            <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
            <input class="btn btn-block btn-primary" type="submit" value="Login">
        </form>



    <!-- Footer -->
<?php include $tpl . 'footer.php'; ?>