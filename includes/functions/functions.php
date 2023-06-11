<?php
// This is the "Frontend Section" functions



/* v stands for Version*/

/* Front-End functions */

/* Getting ALL function v2.0
** A function to get ALL records from any database
*/
// function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderField, $ordering = 'DESC') {
function getAllFrom($field, $table, $orderField, $where = NULL, $and = NULL, $ordering = 'DESC') {
    global $con;

    // $sql = $where == NULL ? NULL : $where; // Or $sql = $where == NULL ? '' : $where;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
    // echo '<pre>', var_dump($getAll), '</pre>';

    $getAll->execute();
    $all = $getAll->fetchAll();


    return $all;
}


/* Getting the latest categories function v1.0
** A function to get the latest categories from the database
*/
/* function getCat() {
    global $con;

    $getCat = $con->prepare('SELECT * FROM `categories` ORDER BY `ID` ASC');
    $getCat->execute();
    $cats = $getCat->fetchAll();


    return $cats;
} */


/* Getting the latest Ad items function v2.0
** A function to get the latest Ad items from the database
*/

/* function getItems($where, $value, $approve = NULL) { // default value for the parameter
    global $con;*/

    /* if ($approve == NULL) {
        $sql = 'AND Approve = 1';
    } else {
        $sql = NULL; // Or $sql = '';
    } */
    // The ternary operator code for the last if condition code
    /* $sql = $approve == NULL ? 'AND Approve = 1' : NULL;

    $getItems = $con->prepare("SELECT * FROM `items` WHERE $where = ? $sql ORDER BY `Item_ID` DESC");
    $getItems->execute(array($value));
    $items = $getItems->fetchAll();


    return $items;
} */


/*
** Checking the Status of the user if approved or pending
** Checking normal user (not Admin) RegStatus
*/
function checkUserStatus($user) {
    global $con;

    // Checking if the user (Normal Users only (not Admins)) exists in the database
    $stmtx = $con->prepare('SELECT `Username` , `RegStatus` FROM `users` WHERE `Username` = ? AND `RegStatus` = 0');
    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();


    return $status;
}


/* Function to check item in database v1.0 (to check if the new user to be added already exists in the database)
** Parameters are: $select (items to be selected. Ex: user, item, category), $from (the table to select from. Ex: users, items, categories), $value (the value of $select. Ex: Ahmed, box, electronics)
*/
function checkItem($select, $from, $value) {
    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();


    return $count;
}









/* Back-End functions*/

/* Title Function v1.0:
** echoes the page's title (in case the page has the variable $pageTitle OR echoes the default title for other pages)
*/
function getTitle() {
    global $pageTitle; // Every file in this project has its own $pageTitle variable which holds its name (at the top of the file)

    if (isset($pageTitle)) {
        return $pageTitle;
    } else {
        return 'Default Title';
    }
}


/* Redirection to HomePage function v1.0
**when any error in the website happens: It accepts the parameters: $errorMsg (to echo the error message) and $seconds (seconds before redirecting)
*/
/* function redirectHome($errorMsg, $seconds = 3) {//3 seconds as a default value for $seconds if not specified
    echo "<div class='alert alert-danger'>$errorMsg</div>";
    echo "<div class='alert alert-info'>You will be redirected to HomePage after $seconds seconds...</div>";
    header("refresh:$seconds;url=index.php"); // Redirection after a certain time u want
    exit();
} */


/* Redirection to HomePage function v2.0
** when any error in the website happens: It accepts the parameters: $theMsg (to echo the message: error, success, warning,... messages) and $seconds (seconds before redirecting) and $url (the link you want to redirect to)
*/
function redirectHome($theMsg, $url = NULL, $seconds = 3) { // 3 seconds as a default value for $seconds if not specified
    if ($url === Null) { // If it is left without anything
        $url  = 'index.php';
        $link = 'HomePage';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') { // This is to avoid the PHP error message that will appear if the user starting from that specific page and there wasn't any page originally coming from
            $url = $_SERVER['HTTP_REFERER']; // the page you are coming from
            $link = 'the Previous Page';
        } else { // Here there is no page $_SERVER['HTTP_REFERER'] can refe to because user is starting from that specific page already
            $url  = 'index.php';
            $link = 'HomePage';
        }
        /* $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php'; */ //The same previous if condition code using the Ternary Operator
    }
    echo $theMsg;
    echo "<div class='alert alert-info'>You will be redirected to $link after $seconds seconds...</div>";
    header("refresh:$seconds;url=$url"); // Redirection after a certain time duration you want
    exit();
}


/* Count number of items in a table function v1.0
** A function to count number of items (rows) (Function that calculates number of rows in any table(Ex: to get the total number of users in the table))
*** Parameters are: $item is items to be counted, $table is the table where you select from
*/
function countItems($item, $table) {
    global $con;

    $stmt2 = $con->prepare("SELECT COUNT(`$item`) FROM `$table`"); // COUNT() is an SQL function
    $stmt2->execute();


    return $stmt2->fetchColumn();
}


/* Getting the latest records function v1.0
** A function to get the latest items from the database (Ex: users, shop items, comments,...)
** Parameters are: $select: the field to select from database, $table: the table to select from and $limit: the number of records to get from the query, $order: ASC or DESC order
*/
function getLatest($select, $table, $order, $limit = 5) {
    global $con;

    $getStmt = $con->prepare("SELECT $select FROM `$table` ORDER BY `$order` DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();


    return $rows;
}