<?php

session_start();

include 'init.php';
// echo 'Categories Page<br>Your Category ID is <strong>' . $_GET['pageid'] . '</strong> and Category Name is <strong>' . str_replace('-', ' ', $_GET['pagename']) . '</strong>'; // Coming from categories in header.php
?>

    <div class="container">
        <div class="row">
<?php
            // $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
            if (isset($_GET['name'])) {
                $tag = $_GET['name'];
                echo '<h1 class="text-center">' . $tag . '</h1>';
                // $tagItems = getAllFrom('*', '`items`', '`Item_ID`', "WHERE `tags` like '%$tag%'", ' AND `Approve` = 1'); // Show all items that belong to that specific tag
                $tagItems = getAllFrom('*', '`items`', '`Item_ID`', "WHERE `tags` like " . "\"%$tag%\"", ' AND `Approve` = 1'); // Show all items that belong to that specific tag
                // echo '<pre>', var_dump($tagItems),'</pre>';
                // exit;

                foreach ($tagItems as $item) {
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
                echo 'You must specify Tag name';
            }
?>      
        </div>
    </div>



<?php
    // Footer
    include $tpl . 'footer.php';
?>