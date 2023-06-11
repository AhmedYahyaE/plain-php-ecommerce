<?php

session_start();

$pageTitle = 'Show Items'; // Check eCommerce/includes/templates/header.php file    AND    eCommerce/includes/functions/functions.php file

include 'init.php';



$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

// Selecting all data depending on that $itemid
$stmt = $con->prepare('SELECT `items`.*, `categories`.Name AS My_Category, `users`.Username AS My_Username FROM `items`
                       INNER JOIN `categories` ON `categories`.ID = `items`.Cat_ID
                       INNER JOIN `users`      ON `users`.UserID  = `items`.Member_ID
                       WHERE `Item_ID` = ? AND `Approve` = 1
                    ');
// echo '<pre>', print_r($stmt), '</pre>';

// Executing the query
$stmt->execute(array($itemid));

$count = $stmt->rowCount();

if ($count > 0) {
    // Retrieving/Fetching data resulted from the query
    $item = $stmt->fetch();
    
?>

    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3"><img class="img-responsive img-thumbnail center-block" src="img.jpg" alt="random image"></div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li><i class="fa fa-calendar fa-fw" aria-hidden="true"></i> <span>Adding Date</span>: <?php echo $item['Add_Date'] ?></li>
                    <li><i class="fa fa-money fa-fw"    aria-hidden="true"></i> <span>Price</span>: <?php echo $item['Price'] ?></li>
                    <li><i class="fa fa-building fa-fw" aria-hidden="true"></i> <span>Made In</span>: <?php echo $item['Country_Made'] ?></li>
                    <li><i class="fa fa-tags fa-fw"     aria-hidden="true"></i> <span>Category</span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['My_Category'] ?></a></li> <!-- Using the SQL INNER JOIN statement -->
                    <li><i class="fa fa-user fa-fw"     aria-hidden="true"></i> <span>Added By</span>: <a href="#"><?php echo $item['My_Username'] ?></a></li> <!-- Using the SQL INNER JOIN statement -->
                    <li class='tags-items'><i class="fa fa-user fa-fw" aria-hidden="true"></i> <span>Tags</span>:
<?php                   
                        $allTags = explode(',', $item['tags']);
                        // echo '<pre>', var_dump($allTags), '</pre>';
                        // exit;

                        foreach ($allTags as $tag) {
                            $tag = str_replace(' ', '', $tag); // to be properly printed in href
                            // echo '<pre>', var_dump($tag), '</pre>';
                            // exit;

                            $lowertag = strtolower($tag); // to be properly printed in href

                            if (!empty($tag)) {
                                echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>'; // check eCommerce/tags.php file
                            }
                        }
?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
<?php
        if (isset($_SESSION['user'])) { // if the current user is authenticated/logged-in    // Protected Routes (Protecting Routes)
?>
            <!--Start: Adding Comment-->
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                            <textarea name="comment" required></textarea>
                            <input class="btn btn-primary" type="submit" value="Add Comment">
                        </form>
<?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // if the HTML Form is submitted with a POST method/verb HTTP Request
                            // $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
                            $comment = htmlspecialchars($_POST['comment']); // https://www.php.net/manual/en/filter.filters.sanitize.php#:~:text=FILTER_FLAG_NO_ENCODE_QUOTES.%20(Deprecated%20as%20of%20PHP%208.1.0%2C%20use%20htmlspecialchars()%20instead.)
                
                            $itemid = $item['Item_ID'];
                            $userid = $_SESSION['uid']; // 'uid' stands for User ID

                            if (!empty($comment)) {
                                $stmt = $con->prepare('INSERT INTO `comments` (`comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES (:zcomment, 0, NOW(), :zitemid, :zuserid)');
                                $stmt->execute(array(
                                    'zcomment' => $comment,
                                    'zitemid'  => $itemid,
                                    'zuserid'  => $userid
                                ));

                                if ($stmt) {
                                    echo '<br><div class= "alert alert-success">Comment Added!</div>';
                                }
                            }
                        }
?>
                    </div>
                </div>
            </div>
            <!--End: Adding Comment-->
<?php
        } else { // if the current user in unauthenticated/logged-out/guest
            echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to add comment';
        }
?>
        <hr class="custom-hr">
<?php
        $stmt = $con->prepare('SELECT `comments`.*, `users`.`Username` AS My_user_name FROM `comments`
                               INNER JOIN `users` ON `users`.`UserID`  = `comments`.`user_id`
                               WHERE `item_id` = ? AND `status` = 1
                               ORDER BY `c_id` DESC
        ');

        // Executing the statement
        $stmt->execute(array($item['Item_ID']));

        // Retrieving/Fetching all the rows and assigning them to a variable
        $comments = $stmt->fetchAll();
        // echo '<pre>', var_dump($comments), '</pre>';

?>
        
<?php
            foreach ($comments as $comment) {
?>
                <div class="comment-box">
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <img class="img-responsive img-thumbnail img-circle center-block" src="img.jpg" alt="random image">
                            <?php echo $comment['My_user_name'] ?>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead"><?php echo $comment['comment'] ?></p>
                        </div>
                    </div>
                </div>
                <hr class="custom-hr">
<?php
            }
?>
    </div>




<?php

} else {
    echo '<div class="alert alert-danger">There\'s no such item ID or this item is waiting for approval by admin</div>';
}


// Footer
include $tpl . 'footer.php'; 

?>