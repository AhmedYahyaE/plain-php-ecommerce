<?php
// This is the "Admin Panel Section" functions



/* v stands for Version */

/* Getting ALL function v2.0
** A function to get ALL records from any database
*/
function getAllFrom($field, $table, $orderField, $where = NULL, $and = NULL, $ordering = 'DESC') {
    global $con;

    //$sql = $where == NULL ? NULL : $where; // Or $sql = $where == NULL ? '' : $where;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

/* Title Function v1.0:
** echoes the page's title (in case the page has the variable $pageTitle OR echoes the default title for other pages)
*/
function getTitle() {
    global $pageTitle; // Every page in this project has its own $pageTitle variable at the top of the page which holds its name

    if (isset($pageTitle)) {
        return $pageTitle;
    } else {
        return 'Default Title';
    }
}


/* Redirection to HomePage function v1.0
** when any error in the website happens: It accepts the parameters: $errorMsg (to echo the error message) and $seconds (seconds before redirecting)
*/
/* function redirectHome($errorMsg, $seconds = 3) { // 3 seconds as a default value for $seconds if not specified
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
    } else { // if it's redirect 'back'
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') { // This is to avoid the PHP error message that will appear if the user starting from that specific page and there wasn't any page originally coming from
            $url  = $_SERVER['HTTP_REFERER']; // the page you are coming from
            $link = 'the Previous Page';
        } else { // Here there is no page $_SERVER['HTTP_REFERER'] can refer to because user is starting from that specific page already
            $url  = 'index.php';
            $link = 'HomePage';
        }
        /* $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php'; */ // The same previous if condition code using the Ternary Operator
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You will be redirected to $link after $seconds seconds...</div>";

    header("refresh:$seconds;url=$url"); // Redirection after a certain time duration you want
    exit();
}


/* Function to check item in database v1.0 (to check if the new user to be added already exists in the database)
**Parameters are: $select (items to be selected. Ex: user, item, category), $from (the table to select from. Ex: users, items, categories), $value (the value of $select. Ex: Ahmed, box, electronics)
*/
function checkItem($select, $from, $value) {
    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();


    return $count;
}


/* Count number of items in a table function v1.0
** A function to count number of items (rows) (Function that calculates number of rows in any table(Ex: to get the total number of users in the table))
*** Parameters are: $item is items to be counted, $table is the table where you select from
*/
function countItems($item, $table) {
    global $con;

    $stmt2 = $con->prepare("SELECT COUNT(`$item`) FROM `$table`");
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




// Get the admin's name based on the Session (to be displayed in the Admin Panel in the navbar on the right in    eCommerce\admin\includes\templates\navbar.php    )
function getAdminNameThroughSession($session_username, $session_id) {
    global $con;

    $statement = $con->prepare("SELECT `FullName` FROM `users` WHERE `Username` = :session_username AND `UserID` = :session_id AND `GroupID` = 1 LIMIT 1;"); // `GroupID` = 1 to make sure the user is an Admin    // LIMIT 1    because it must be a unique one user
    // return $statement;
    /* echo '<pre>', var_dump($statement),'</pre>';
    exit; */

    $statement->bindValue(':session_username', $session_username, PDO::PARAM_STR);
    $statement->bindValue(':session_id'      , $session_id      , PDO::PARAM_INT);

    /* echo '<pre>', var_dump($statement->execute()),'</pre>';
    exit; */
    // return $statement->execute();
    $statement->execute();


    /* echo '<pre>', var_dump($statement->fetch()),'</pre>';
    // echo '<pre>', var_dump($statement->fetch(PDO::FETCH_ASSOC)),'</pre>';
    // echo '<pre>', var_dump($statement->fetchColumn()),'</pre>';
    exit; */
    // return $statement->fetch();
    // return $statement->fetch(PDO::FETCH_ASSOC);
    return $statement->fetchColumn();
}