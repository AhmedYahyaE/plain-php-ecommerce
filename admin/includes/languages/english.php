<?php


function lang($phrase) {

    static $lang = array( // use of static here to prevent mutiple reallocations and better performance    // static variables in functions context: https://www.php.net/manual/en/language.variables.scope.php#language.variables.scope.static
        // navbar.php links
        'HOME_ADMIN' => 'Home',
        'CATEGORIES' => 'Categories',
        'ITEMS'      => 'Items',
        'MEMBERS'    => 'Members',
        'Comments'   => 'Comments',
        'STATISTICS' => 'Statistics',
        'LOGS'       => 'Logs'
    );


    return $lang[$phrase];
}