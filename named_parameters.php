<?php
/*

"Named parameters" in PHP. There is no such thing, but we can "simulate" it with arrays.
In Python we can do:
def info(x = 1, y = 2, z = 3):
and then call it like this:
info(x = 100)

We can't do that in PHP, but we can simply pass an array with key => value pairs.

This is a working code - https://3v4l.org/Yj8vT
*/ 

/**
 * This function will take care of two problems, i.e. we can pass it an array with default values
 * plus - the only keys we expect.
*/
function namedParams (array $params, array $defaults){
    $params += $defaults;
    $allowedKeys = array_keys($defaults);
    $isUnexpected = array_filter($params, function ($k) use ($allowedKeys){
        return !in_array($k, $allowedKeys);
    }, ARRAY_FILTER_USE_KEY);
    if(!empty($isUnexpected)){
        throw new InvalidArgumentException('Unexpected parameters: '.implode(',',array_keys($isUnexpected))); 
    }    
    return $params;
} 

function test (array $params){
    //we can try ... and catch any problems if we want, but to keep it simple, we can just do this.
    //These and only these params are expected. Plus, we give them default values.
    $paramsProcessed = namedParams($params,[
        'name' => 'Unknown',
        'age' => 17,
        'something' => NULL,
    ]); 
    var_dump($paramsProcessed);
}

test(['name' => "Jessica"]);
//This would throw - Uncaught InvalidArgumentException: Unexpected parameters: blah 
//test4(['name' => "Konrad", 'blah' => 'oh']);
