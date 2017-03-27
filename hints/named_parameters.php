<?php
/**
"Named parameters" in PHP. There is no such thing, but we can "simulate" it with arrays.
In Python we could do:
def info(x = 1, y = 2, z = 3):
and then call it like this:
info(x = 100)
We can't do that in PHP, but we can do this.
Note - this is a working code - https://3v4l.org/qe9lO
*/ 
 
function test1($params){
    //now we can do this:
    echo "name: {$params['name']} \n";
    
    //or import the "params" array into variables:
    extract($params);
    
    //and then:
    echo "name: $name \n";
    echo "age: $age \n";
}

//now we call the function using an array: 
test1 (['name' => 'Marry', 'age' => 37]);

echo "\n----------------problem #1 - default values------------------\n\n";

//#1. defaults
//I.e. what if we didn't pass 'name' in our function call? 
//In our function we could do something like 
//if(!isset($params['name'])) {$params['name'] = 'Unknown';}; 
//but this is a much better and cleaner way:

function test2($params){
    //default values:
    $params += [
        'name' => 'Unknown',
        'age' => 37,
    ]; 
    //import the "params" array into variables:
    extract($params);
    
    //and then:
    echo "name: $name \n";
    echo "age: $age \n";
}

//now we pass only the 'age' param, the 'name' will be set to 'Unknown':
test2(['age' => 20]);

echo "\n----------------problem #2 - unexpected parameters------------------\n\n";

//Another problem is we only want to accept specific parameters (keys) - otherwise we want to generate an error:
function test3($params){
    $allowed = ['name', 'age'];
    $givenKeys = array_keys($params);
    $isUnexpected = array_filter($params, function ($v, $k) use ($allowed){
        return !in_array($k, $allowed);
    }, ARRAY_FILTER_USE_BOTH);
    if(!empty($isUnexpected)){
        throw new InvalidArgumentException('Unexcpected parameters: '.implode(',',array_keys($isUnexpected))); 
    }    
    
    //import the "params" array into variables:
    extract($params);
    
    echo "name: $name \n";
    echo "age: $age \n";
}
//This will be OK:
test3(['name' => 'Marry', 'age' => 28]);

//This would generate an exception about 'unexpected blah parameter' 
//test3(['name' => 'Marry', 'age' => 28, 'blah' => 'bar']);


echo "\n----------------Combined into a utility function------------------\n\n";

//This simple function will take care of both problems, i.e. we pass it an array with default values
//plus - the only keys we expect.
function namedParams ($params, $defaults){
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

function test4 ($params){
    //we can try ... and catch any problems if we want, but to keep it simple, we can just do this.
    //These and only these params are expected. Plus, we give them default values.
    $paramsProcessed = namedParams($params,[
        'name' => 'Unknown',
        'age' => 17,
        'something' => NULL,
    ]); 
    var_dump($paramsProcessed);
}

test4(['name' => "Konrad"]);
//This would throw - Uncaught InvalidArgumentException: Unexpected parameters: blah 
//test4(['name' => "Konrad", 'blah' => 'oh']);
