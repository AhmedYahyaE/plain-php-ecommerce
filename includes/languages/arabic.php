<?php


function lang($phrase) {

    static $lang = array( // use of static keyword here to prevent mutiple reallocations and better performance    // static variables in functions context: https://www.php.net/manual/en/language.variables.scope.php#language.variables.scope.static
        // navbar.php links
        'MESSAGE'  => 'أهلا',
        'ADMIN'    => 'الأدمن',
        'Comments' => 'التعليقات'
    );


    return $lang[$phrase];
}