# PDO _Automated Data Binding_
Insert and retrieve data dynamically using PDO. It takes care of all the logistics and headache of binding fields and data manually, fields are still binded but they are done instantaneously and dynamically without user interference

Follow the following steps to implement this usage if you aren’t already familiar with php

#Running Demo

Step 1
Download this repository, create a local server and a database of your choice if you don’t already have one

Step 2
Add plugins folder to your server root, e.g. if you are running xampp : add to httpdoc and if wampp: add to www and access the folder in 
your browser. localhost/quickQuery for a quick demo

#Usage Implementing in your environment / SETUP

Step 1
Copy the php folder to your project development environment and open php/classes/process.php

Step 2 change the following code
 - Define a database connection 
 - 
 ```php
  private $DB_HOST =     "Whatever server";
  private $DB_NAME =     "whatever databse name";
  private $DB_USER =     "Whatever username";
  private $DB_PASSWORD = 'Whatever Password';
  ```
  
  Step 3 call the $process object class where you would like to use it
#Using default database specified within the class, this can be a general use database
```php
 $process->quickQuery("foo@bar.com","145.814.964.2", 547g7recv24s5fd47sf54s, 2015-11-2015, array('table'=>'subscribers',"fields"=>'email, ip_address, unsubscribe_key, added_on'));
 ```
 * where foo@bar is the data you would like to insert in the field; "fields"=>'email and so forth
 * Another example, 145.814.964.2 is the ip you would like to insert in the field;"fields"=>ip 
  
 # Specifying an alternative database for different uses, perhaps your mailing list isn’t within the same database as your member     table, this class and methods can be called from anywhere

 ```php
 $dbInfo = array('DB_HOST'=>'localhost','DB_NAME'=>'user_db','DB_USER'=>'root','DB_PASSWORD'=>'testing');
 $process->quickQuery("foo@bar.com","145.814.964.2", 547g7recv24s5fd47sf54s, 2015-11-2015, array('db'=>$dbInfo, table'=>'subscribers',"fields"=>'email, ip_address, unsubscribe_key, added_on'));
 ```
* a variable that holds the array of the database connection '$dbInfo' was then passed into quickQuery(..., array('db'=>$dbInfo, ...)
* if a database isnt specified it would use the default database.
 
other bits and pieces added that may be of use, such as strict email validation, not so strong text validation, isSubscribed function. Alot more useful things to be added in due time.

#Note
If you are experiencing issues, check that this file exists ('php/classes/db/db.bindParam.php) and giving the appropriate permissions
