        <nav class="navbar navbar-inverse">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN') ?></a> <!-- lang() function is declared in the language files english.php and arabic.php -->
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="nav navbar-nav">
                        <li><a href="categories.php"><?php echo lang('CATEGORIES') ?></a></li> <!-- lang() function is declared in the language files english.php and arabic.php -->
                        <li><a href="items.php"><?php echo lang('ITEMS') ?></a></li>           <!-- lang() function is declared in the language files english.php and arabic.php -->
                        <li><a href="members.php"><?php echo lang('MEMBERS') ?></a></li>       <!-- lang() function is declared in the language files english.php and arabic.php -->
                        <li><a href="comments.php"><?php echo lang('Comments') ?></a></li>     <!-- lang() function is declared in the language files english.php and arabic.php -->

                        <li><a href="/eCommerce/index.php"><b>Go to the Main Website</b></a></li> <!-- is the same as:    <li><a href="../index.php">Go to the Main Website</a></li>    -->
                        <!-- <li><a href="../index.php">Go to the Main Website</a></li> -->         <!-- is the same as:    <li><a href="/eCommerce/index.php">Go to the Main Website</a></li>    -->       

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php // echo '<pre>', var_dump(getAdminNameThroughSession($_SESSION['Username'], $_SESSION['ID'])->queryString), '</pre>' ?> <span class="caret"></span></a> -->
                            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php // echo '<pre>', var_dump(getAdminNameThroughSession($_SESSION['Username'], $_SESSION['ID'])), '</pre>' ?> <span class="caret"></span></a> -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo getAdminNameThroughSession($_SESSION['Username'], $_SESSION['ID']) ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../index.php">Visit Shop</a></li>
                                <!-- The FrontEnd index.php -->
                                <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li> <!-- Edit the currently authenticated/logged-in user -->
                                <li><a href="#">Settings</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <?php
            // echo "<pre>MY SESSION ARRAY ELEMENTS ARE: \n", print_r($_SESSION), ' (from the Admin Panel in admin/includes/templates/navbar.php)</pre>'; 
        ?>