<?php
/*
 overcomplicated script checking your PHP's error logging configuration.
 just copy and paste the following code to some file and run it.
  
 the script doesn't change anything, it just reads the configuration and generates a small report.   

 example report:
  
    No errors found

    ok : php_ini_loaded_file test is fine (/etc/php/7.0/apache2/php.ini)
    ok : error_reporting test is fine (-1)
    ok : display_errors test is fine (1)
    ok : log_errors test is fine (1)
    ok : error_log test is fine (/tmp/php_errors.log)
    ok : error_log_exists test is fine (/tmp/php_errors.log)
    ok : error_log_writable test is fine (/tmp/php_errors.log)
 *   
 */
//we'll need it a few times:
$errorLogFilePath = ini_get('error_log');

$validation = [
	'php_ini_loaded_file' => [
            'val' => php_ini_loaded_file(),
            'rules' => 'required',
	],
	
	'error_reporting' => [
            'val' => ini_get('error_reporting'),
            'rules' => [
                'matches' => -1,
            ],
	],
	'display_errors' => [
            'val' => ini_get('display_errors'),
            'type' => 'warning',//exception
            'rules' => 'required',
            //optional additional message
            'msg' => 'It\'s (maybe) worth enabling only in dev env.',
	],   
    
        //logging errors on/off
	'log_errors' => [
            'val' => ini_get('log_errors'),
            'rules' => 'required',
	],     
        //error log file
	'error_log' => [
            'val' => $errorLogFilePath,
            'rules' => 'required',
	], 
        
        'error_log_exists'=> [
            'val' => $errorLogFilePath,
            'rules' => function($val = ''){
                return file_exists($val) ? true : "Log file $val doesn't exist";
            }
        ],
        'error_log_writable'=> [
            'val' => $errorLogFilePath,
            'rules' => function($val = ''){
                return is_writable($val) ? true : "Log file $val isn't writable";
            }
        ],
];

//////////////
function isCLI(){
    return (php_sapi_name() === 'cli');
}

/*
 * @param $flags string - h1,error,small
 */
function echoNL($str = '', $flags = ''){
    if(isCLI()){
        echo "{$str}\n";
    }else{
        $style = '';
        $tag = 'span';
        if(strpos($flags,'h1') !== false){
            $tag = 'h1';
        }
        if(strpos($flags,'error') !== false){
            $style .= 'color:red';
        }
        if(strpos($flags,'small') !== false){
            $style .= 'color:#333;font-size:80%';
        }       
        echo "<{$tag} style = '$style'>{$str}</{$tag}><br>";
    }
}

$result = [];
$errorCounter = 0;

foreach($validation as $name => $item){
    $error = null;
    
    if(is_callable($item['rules'])){
        $callbackResult = $item['rules']($item['val']);
        if($callbackResult !== true){
           $error =  $callbackResult;
        }
    }elseif($item['rules'] === 'required' && empty($item['val'])){
            $error = "$name is disabled";
    }elseif(isset($item['rules']['matches'])){
        if($item['val'] != $item['rules']['matches']){
            $error = "$name should be equal to {$item['rules']['matches']}";
        }
    }
    
    if($error === null ){
        $msg = '';
        $result []= ['type' => 'ok', 'msg' => "$name test is fine ({$item['val']})"];
    }else{
        //error(default)/warning/other
        $type = (isset($item['type'])? $item['type'] : 'error');
        $result []= [
            'type' => $type, 
            'msg' => $error . (isset($item['msg'])? '. '.$item['msg'] : '')];
        if($type == 'error'){
            $errorCounter++;
        }
    }
   
}


echoNL('Testing configuration:','h1');

if($errorCounter === 0){
    echoNL ("No errors found");
}else{
    echoNL ("Errors (or warnings) found!");
}
echoNL();
foreach ($result as $resultItem){
    $type = $resultItem['type'];
    $flags = '';
    if($type === 'error'){
        $flags .= 'error';
    } 
    echoNL("{$type} : {$resultItem['msg']}",$flags);

}

//final error logging test.
//we'll temporarily disable displaying errors, we only want to check if this will be logged:
ini_set("display_errors", 0); 
$x = 2/0;
//restoring display_errors.
ini_set("display_errors", $validation['display_errors']['val']); 

if(is_writable($errorLogFilePath)){
    echoNL('Last error log file lines:','h1');
    echoNL('You should see "(...) Division by zero (...)"');
    echoNL();
    $fp = fopen($errorLogFilePath, 'r');
    fseek($fp, -1000, SEEK_END);
    $count = 0;
    while($line = fgets($fp)){
        if($count++ === 0){
            continue;//we omit the first line
        }
        if(!isCLI()){
            $line = htmlspecialchars($line);
        }
        echoNL($line,'small');
    }
}




