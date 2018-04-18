<?php

/*
Returns an execution time in microseconds (one millionth (0.000001 or 1/1,000,000) of a second). 

Example: this should take about 4 seconds, because we repeat it 2 times: 
echo benchmark (function(){
    sleep(1);
    sleep(1);
}, 2);

outputs something like: 4000000

@param $callable - the (anonymouse) function to test
@param $iterations - how many times should we run the code
@return float
*/
function benchmark (callable $c, int $iterations = 1, int $precision = 0){
    $st = microtime(true);
    for ($n = 0; $n < $iterations; $n++){
        $c();
    }
    
    /*
    Here we multiply by 1000 000 because 
    "If get_as_float is set to TRUE (second param in microtime()), then microtime() returns a float, which represents 
    the current time in SECONDS since the Unix epoch ACCURATE to the nearest MICROSECOND." 
    */
    return round((microtime(true)-$st)*1000*1000,$precision);
}
