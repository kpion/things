<?php

/*
A very simple implementation of 'typed array', like this:

$stringArray = new TypedArray('string');
and now only strings can be added to the above $stringArray. Works with objects as well.

More examples below the class.
Ah, and here is a working example: https://3v4l.org/kHocv (can be outdated though)

*/
class TypedArray extends \ArrayIterator
{
    protected $type = '';

    public function __construct(string $type, array $input = [], int $flags = 0){
        $this->type = $type;
        parent::__construct($input, $flags);
    }
    
    /*
    Just an utility funciton. In case it's a scalar (like int), or array etc - returns that
    if it's an object, returns the name of the class.
    */
    public function getTypeOrClassName($val){
        $testType = gettype($val); 
        if($testType === 'object'){
            return get_class($val);
        }
        return $testType;
    }
    
    public function checkType($val){
        return $this->getTypeOrClassName($val) === $this->type;
    }
    
    
    public function getType(){
        return $this->type;
    }
    
    public function offsetSet($offset, $value) {
        if(!$this->checkType($value)){
            throw new \InvalidArgumentException('This TypedArray accepts only "' . $this->type . '" type, "' . $this->getTypeOrClassName($value) . '" given');
        }
        return parent::offsetSet($offset, $value);
    }
}

//////////////////////////////////////////////////////////////////////////////
//examples

$stringArray = new TypedArray('string');
$stringArray []= 'first string';
$stringArray []= 'second string';

//$stringArray [] = 2;//won't work - exception thrown

var_dump((array)$stringArray);

//tests with objects as values

class User{
    public $name;
    
    public function __construct($name){
        $this->name =  $name;
    }
}

$userArray = new TypedArray(User::class);
$userArray[]= new User('John');

//$userArray []= new \StdClass();//exception thrown

echo $userArray[0]->name;
