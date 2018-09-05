# Things.

That is, some hints, things, and playing with github.

---

## path_array.php
https://github.com/kpion/things/blob/master/path_array.php

PathArray class - enables working on 'dot notation'  like:
```
$exampleInputData = ['user' => ['name' => 'John', 'sex' => 'male'] ];
echo PathArray::get($exampleInputData, 'user.name');// (We can use different separators than '.')
```

Working example: https://3v4l.org/kYeN9


## typed_array.php 
https://github.com/kpion/things/blob/master/typed_array.php

TypedArray class - A very simple implementation of 'typed array', like this:
```
$stringArray = new TypedArray('string');
and now only strings can be added to the above $stringArray. Works with objects as well.
```
More examples below the class.

Ah, and here is a working example: https://3v4l.org/kHocv (can be outdated though)


## check_stuff.php
https://github.com/kpion/things/blob/master/check_stuff.php


Overcomplicated script checking your PHP's error logging configuration.
just copy and paste the above code to some file and run it.


## code_benchmark.php
https://github.com/kpion/things/blob/master/code_benchmark.php

Returns an execution time in microseconds (one millionth (0.000001 or 1/1,000,000) of a second). 

Example: this should take about 4 seconds, because we repeat it 2 times: 
```
echo benchmark (function(){
    sleep(1);
    sleep(1);
}, 2);
```

## error_reporting.md
https://github.com/kpion/things/blob/master/error_reporting.md

How to make PHP errors/warning to log/display

## named_parameters.php
https://github.com/kpion/things/blob/master/named_parameters.php

"Named parameters" in PHP. There is no such thing, but we can "simulate" it with arrays.

In Python we can do:
```
def info(x = 1, y = 2, z = 3):
and then call it like this:
info(x = 100)
```
We can't do that in PHP, but we can simply pass an array with key => value pairs.

This is a working code - https://3v4l.org/Yj8vT

## var_dump_str.php
https://github.com/kpion/things/blob/master/var_dump_str.php

var_dump returning a string, in case we need it, for example to log it to a file, example:
``` 
error_log("Something something: ", var_dump_str($someArray)); 
```
Working example: https://3v4l.org/BGCgH

---


