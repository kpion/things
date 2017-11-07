<?php

/*
Returns an execution time in microseconds (one millionth (0.000001 or 1/1,000,000) of a second). 

Example: this should take about 4 seconds, because we repeat it 2 times: 
echo benchmark (function(){
    sleep(1);
    sleep(1);
}, 2);

@param $callable - the (anonymouse) function to test
@param $iterations - how many times should we run the code
@return float
*/
function benchmark (callable $c, int $iterations = 1, int $precision = 0){
    $st = microtime(true);
    for ($n = 0; $n < $iterations; $n++){
        $c();
    }
    return round((microtime(true)-$st)*1000*1000,$precision);
}
