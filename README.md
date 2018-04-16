# Things.

That is, some hints and playing with github.

---
## PathArray.php
https://github.com/konradpapala/things/blob/master/hints/PathArray.php

PathArray enables working on 'dot notation'  like:
$exampleInputData = ['user' => ['name' => 'John', 'sex' => 'male'] ],
echo PathArray::get($exampleInputData, 'user.name');// (We can use different separators than '.')
More example usages below the class.
Working example: https://3v4l.org/kYeN9

---

## TypedArray.php
https://github.com/konradpapala/things/blob/master/hints/TypedArray.php

A very simple implementation of 'typed array', like this:
$stringArray = new TypedArray('string');
and now only strings can be added to the above $stringArray. Works with objects as well.

More examples below the class.

Ah, and here is a working example: https://3v4l.org/kHocv (can be outdated though)

---

## check_stuff.php
https://github.com/konradpapala/things/blob/master/hints/check_stuff.php


overcomplicated script checking your PHP's error logging configuration.
just copy and paste the following code to some file and run it.

---

## code_benchmark.php
https://github.com/konradpapala/things/blob/master/hints/code_benchmark.php

Returns an execution time in microseconds (one millionth (0.000001 or 1/1,000,000) of a second). 

Example: this should take about 4 seconds, because we repeat it 2 times: 
echo benchmark (function(){
    sleep(1);
    sleep(1);
}, 2);

---
## error_reporting.md
https://github.com/konradpapala/things/blob/master/hints/error_reporting.md

How to get PHP errors to log/display

---
## named_parameers.php
https://github.com/konradpapala/things/blob/master/hints/named_parameters.php

"Named parameters" in PHP. There is no such thing, but we can "simulate" it with arrays.

In Python we can do:
def info(x = 1, y = 2, z = 3):
and then call it like this:
info(x = 100)

We can't do that in PHP, but we can simply pass an array with key => value pairs.

This is a working code - https://3v4l.org/Yj8vT

## More...

Also: https://konradpapala.github.io