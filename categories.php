<?php

session_start();

$pageTitle = 'Categories'; // Check eCommerce/includes/templates/header.php file    AND    eCommerce/includes/functions/functions.php file

include 'init.php';
// echo 'Categories Page<br>Your Category ID is <strong>' . $_GET['pageid'] . '</strong> and Category Name is <strong>' . str_replace('-', ' ', $_GET['pagename']) . '</strong>'; // Coming from categories in header.php
?>

    <div class="container">
        <h1 class="text-center">Show Category Items<?php /* echo str_replace('-', ' ', $_GET['pagename']) */ ?></h1>
        <div class="row">
<?php
            // $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
            // If a category is clicked in header.php which, in turn, include-ed in index.php
            if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) { // pageid is the `id` column in `categories` table
                $category = intval($_GET['pageid']);

                $allItems = getAllFrom('*', '`items`', '`Item_ID`', "WHERE `Cat_ID` = {$category}", 'AND `Approve` = 1', '');
                // $allItems = getAllFrom('*', '`items`', "WHERE `Cat_ID` = {$category}", 'AND `Approve` = 1', '`Item_ID`');

                foreach ($allItems as $item) {
                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="thumbnail item-box">';
                            echo '<span class="price-tag">' . $item['Price'] . '</span>';
                            echo '<img class="img-responsive" src="img.png" alt="random image">';
                            echo '<div class="caption">';
                                echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                                echo '<p>' . $item['Description'] . '</p>';
                                echo '<div class="date">' . $item['Add_Date'] . '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }

            } else {
                echo 'You must specify Page ID';
            }
?>      
        </div>
    </div>



<?php
include $tpl . 'footer.php'; 
?>