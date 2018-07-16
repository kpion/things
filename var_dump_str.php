<?php
/*
var_dump returning a string, in case we need it, for example to log it to a file, example:
error_log("Something something: ", var_dump_str($someArray));
working example: https://3v4l.org/BGCgH
*/
function var_dump_str(...$v){
    ob_start();
    var_dump(...$v);
    return ob_get_clean();    
}