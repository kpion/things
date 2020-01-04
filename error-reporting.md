
# How to make PHP to log and optionally display all the notices/warnings/errors.


## TL;DR:

Put/modify this in the php.ini file:

```
error_reporting = -1
display_errors = On
display_startup_errors = On
```

And restart your apache/whatever you use.

## Detailed version:

### I. Globally - with php.ini file

This is the more reliable solution, because you'll be able to monitor all the issues, including parse (syntax) errors.


#### Edit php.ini file

If you don't know where is this php.ini file located, create a new file like info.php and put this there: 

```php
<?php phpinfo();?>
```

Open it in your browser. Under "Loaded Configuration File" you'll find the path to the ini file.

Now put or modify this in the php.ini file:

```ini
error_reporting = -1
```

and this, if you want to display the errors directly in output, which might be a good idea in dev environment:

```ini
display_errors = On
display_startup_errors = On
```

You definitely want the issues to be logged to a file:

```ini
log_errors = On
```

Optional log file location - the below path is just an example. You might need to create this file by yourself, with the right persmissions. 


```ini
error_log = /var/log/php.log
```

By default, you can see the errors in your webserver logs. E.g. on Debian with Apache, you'll find them in `/var/log/apache2/error.log`

#### Restart your webserver/fastcgi-listeners 		


Restarting Apache on Debian and Debian-based (e.g. Ubuntu): 
```
sudo service apache2 restart
```

Restarting Apache on OS X: 
```
sudo apachectl -k restart 
```


### II. Per script, at runtime

Use this in case you want to change the settings at runtime, or you don't have access to php.ini file for any reason.


Put this at the top of your script

```php
ini_set('display_errors', 1);
error_reporting(-1);
```

Side note: when using this method you will not see syntax errors.


## III. Post scriptum
 
### Monitoring logs 

You can easily "watch" the log file, like this:

```bash
tail -f /var/log/php.log
```

### Two environment setups

You might want to have two setups, one for dev environment, and one for production. 

In the latter you don't want to *display* errors. But you want them logged somewhere.

So you can create two versions of php.ini file  and in the production version put display_errors = Off and display_startup_errors = Off 

Or, you can do something like this in PHP:

```php	
//this constant might also come from an ini or .env file
define('ENVIRONMENT','DEV');

//these are common settings, for both environments, even on production we want to have 
//warnings logged to a file, 
//we just don't want to see them on the screen 

error_reporting(-1);
ini_set ('log_errors',1);
ini_set ('error_log','/var/log/php.log');

if (ENVIRONMENT === 'DEV'){
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
}else{//production env
	ini_set('display_errors',0);
	ini_set('display_startup_errors',0);
}
```

### Handling PDO errors

If you're working with PDO, note that by default, it's setup to be silent. I.e. the PDO::ATTR_ERRMODE is set to ERRMODE_SILENT. 

You want to change it to:

```php
$db = new PDO("mysql:host=localhost;dbname=DATABASE_NAME",'USER','PASSWORD',[
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
```

Side note: PDO::\_\_construct will always throw an exception when the connection fails.

## References
http://php.net/errorfunc.configuration
