<?php

/*
PathArray enables working on 'dot notation'  like:
$exampleInputData = ['user' => ['name' => 'John', 'sex' => 'male'] ];
echo PathArray::get($exampleInputData, 'user.name');// (We can use different separators than '.')
More example usages below the class.
Working example: https://3v4l.org/kYeN9

*/


class PathArray
{
    
    /*
    Used mostly internally, will return keys - that is, if 'path' is already an array, it will return it.
    if it's a string it will explode it by $separator
    */
    public static function pathKeys ($path, $separator = '.'){
        if(is_array($path)){
            return $path;
        }
        return explode($separator,$path);
    }
    
    /*
    @param string|array $path a string like node1.node2.node3 or an array with keys.
    */
    public static function exists(array $data, $path){
        $result = $data;
        foreach(self::pathKeys($path) as $key) {
            if(!isset($result[$key])){
                return false;
            }
            $result = $result[$key];  
        }
        return true;
    }

    /*
    example: PathArray::get($data, 'user.name'));//'John'
    @param string|array $path a string like node1.node2.node3 or an array with keys.
    */
    public static function get(array $data, $path, $default = NULL){
        $result = $data;
        foreach(self::pathKeys($path) as $key) {
            if(!isset($result[$key])){
                return $default;
            }
            $result = $result[$key];   
        }
        return $result;
    }
    
    /*
    Creates a new key - value, or modifies an existing one.
    @param string|array $path a string like node1.node2.node3 or an array with keys.
    */    
    public static function set(array &$data, $path, $val){
        $result = &$data;
        foreach(self::pathKeys($path) as $key) {
            $result = &$result[$key];   
        }
        $result = $val;
        return $data;
    }
}


//Example usage
//Some sample data.
$data = [
   'user' => ['name' => 'John', 'sex' => 'male', 'children' => ['Mary', 'Robert'] ],
   'file' => ['name' => 'funny_cat']
];


var_dump(PathArray::exists($data, 'user.name'));//true
var_dump(PathArray::exists($data, 'nonexisting'));//false

var_dump(PathArray::get($data, 'user.name'));//'John'
var_dump(PathArray::get($data, PathArray::pathKeys('user/name','/')));//different separator, also returns 'John'
var_dump(PathArray::get($data, 'user.children'));//an array with two values, Mary, Robert
var_dump(PathArray::get($data, 'user.children.0'));//Mary
var_dump(PathArray::get($data, 'user.children.10'));//doesn't exist, default value returned (in this case - null)

PathArray::set($data, 'file.name', 'funny_dog');//we change existing "funny_cat" to "funny_dog"
PathArray::set($data, 'file.size', '1000');//we set a new key and value

var_dump($data);
