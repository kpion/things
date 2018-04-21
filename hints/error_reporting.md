```text
# How to get PHP errors to log/display

TL;DR:

Put/modify this in the php.ini file:
error_reporting = -1
display_errors = On

Long version:

1. In case you have an access to the php.ini file:

	1a. Edit your php.ini file

		If you don't know where is this php.ini file located, create a new file like blah.php 
		and put this there: 
		
		<?php phpinfo();
		Open it in your browser
		Under "Loaded Configuration File" you'll find the path to the ini file

		Now put or modify this in the php.ini file:

		error_reporting = -1
		display_errors = On
		;if you want to display the errors directly in output (e.g. page): 
		display_startup_errors = On
		log_errors = On
		
		;the below path is just an example
		;YOU NEED TO CREATE THIS FILE IF IT'S NOT THERE! PHP won't do this for you.
		error_log = /tmp/php_errors.log

		

	1b. Restart your webserver/fastcgi-listeners. 
		Restarting Apache:
			Debian and Debian-based (e.g. Ubuntu): sudo service apache2 restart
			OS X: sudo apachectl -k restart 


		Side note: The above solution is better than using ini_set() and error_reporting() 
		in the script (described below),becase the latter will fail 
		if there are parse errors. 


2. In case you don't have access to php.ini file for any reason:

	2a. Put this at the top of your script

		ini_set('display_errors', 1);
		error_reporting(-1);

	2b. That's all.

	Side note: when using this method you will not see syntax errors.
 
3. More side notes
 
 	### 3a. You can easily "watch" the log file, like this:
 		tail -f /tmp/php_errors.log

	### 3b. You should consider having two setups, one for dev environment, and one for production. 
	In the latter you don't want to *display* errors, so you can create two versions of php.ini file 
	and in the production version put display_errors = Off and display_startup_errors = Off 
	or in the php code:
	
		define('ENVIRONMENT','DEV');//or 'PROD';
		
		//these are for both environments, even on production we want to have 
		//the errors logged to a file, 
		//we just don't want them to be displayed on the screen 
		error_reporting(-1);
		ini_set ('log_errors',1);
		ini_set ('error_log','/tmp/php_errors.log');
		
		if (ENVIRONMENT === 'DEV'){
			ini_set('display_errors',1);
			ini_set('display_startup_errors',1);
		}else{//production env
			ini_set('display_errors',0);
			ini_set('display_startup_errors',0);
		}

	3c. There is one thing with PDO. By default, PDO::ATTR_ERRMODE is set to ERRMODE_SILENT. 
	This means, if there is an error in SQL/Whatever, PDO will throw no exceptions and 
	it will issue no warnings. 
	You want to change it to:
	
    	$dbh = new PDO("mysql:host=localhost;dbname=DATABASE_NAME",'USER','PASSWORD',[
		//this is if you want exceptions to be thrown, you can also use 
		//PDO::ERRMODE_WARNING to generate warnings.
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION;
	]);	
	
side note: PDO::__construct will always throw an exception if the connection fails.

```
