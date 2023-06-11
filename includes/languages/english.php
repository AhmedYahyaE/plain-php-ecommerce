<?php

// From ChatGPT: In the given PHP code, the function lang() is defined. This function takes a $phrase parameter and returns the corresponding value from the static $lang array. The static variable $lang is declared within the function and assigned an array value. By using the static keyword, the variable is allocated only once and retains its value between multiple invocations of the lang() function. This helps improve performance by avoiding redundant allocations of the $lang array on each function call. The $lang array in this example appears to store translations for different phrases used in a website or application. Each key in the array represents a specific phrase, and the corresponding value represents the translated version of that phrase. For example, if you call the function lang('HOME_ADMIN'), it will return the value 'Home', which is the translation of the phrase 'HOME_ADMIN'. Similarly, you can pass other keys as arguments to retrieve their respective translations from the $lang array. By using a static variable, the translations stored in the $lang array can be efficiently accessed and reused across multiple calls to the lang() function without needing to reload or redefine the translations each time the function is called.

function lang($phrase) {

    static $lang = array( // use of static keyword here to prevent mutiple reallocations and better performance    // static variables in functions context: https://www.php.net/manual/en/language.variables.scope.php#language.variables.scope.static
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