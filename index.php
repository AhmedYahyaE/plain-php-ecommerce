<?php

session_start();

$pageTitle = 'HomePage'; // Check eCommerce/includes/templates/header.php file    AND    eCommerce/includes/functions/functions.php file

include 'init.php';

?>



        <div class="container">
            <div class="row">
<?php
                // $allItems = getAllFrom('*', '`items`', 'WHERE `Approve` = 1', '', '`Item_ID`');
                $allItems = getAllFrom('*', '`items`', '`Item_ID`', 'WHERE `Approve` = 1', ''); // Show all the approved (column `Approve` = 1 ) items (`items` table)

                foreach ($allItems as $item) {
                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="thumbnail item-box">';
                            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                            echo '<img class="img-responsive" src="img.jpg" alt="random image">';
                            echo '<div class="caption">';
                                echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                                echo '<p>' . $item['Description'] . '</p>';
                                echo '<div class="date">' . $item['Add_Date'] . '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
?>
            </div>
        </div>




<?php

// Footer
include $tpl . 'footer.php'; 
?>