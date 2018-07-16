<?php

/*
var_dump version which returns a string, in case we need it, for example to log it to a file, example:
error_log("Something something: ", var_dump_str($someArray));
*/
function var_dump_str(...$v){
    ob_start();
    var_dump(...$v);
    return ob_get_clean();    
}