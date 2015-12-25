<?php

error_reporting(E_ALL &~E_NOTICE);

/**
 * Created by PhpStorm.
 * User: Martin Onyesom
 * Date: 03/11/2015
 * Time: 16:08
 */
class process
{

    //Database information
    /**
     * @var $DB_HOST String database server address, aka hostname
     * @var $DB_NAME String name of the database you wish to connect to
     * @var $DB_USER String name of the user who would want to initial the connection
     * @var $DB_PASSWORD String password of the user who wants to initial the connection     *
     * @var $dbInfo String password of the user who wants to initial the connection     *
     */
    private $DB_HOST = "localhost";
    private $DB_NAME = "faceback";
    private $DB_USER = "root";
    private $DB_PASSWORD = '';
    private static $dbInfo = array();


    public $pdo;
    public $create;
    protected $success;
    protected $statusMessage;
    public $isUserSubscribed;


    /**
     * @var $host String database server address, aka hostname
     * @var $name String name of the database you wish to connect to
     * @var $user String name of the user who would want to initial the connection
     * @var $pass String password of the user who wants to initial the connection
     * @return PDO database return pdo object with database information for use of generating different data
     */
    function connect(){
        $error = "Database: connection could not be made, please check the following error!- ";

        if(is_array(self::$dbInfo) && !empty(filter_var_array(self::$dbInfo))){
            $host = self::$dbInfo[strtoupper('DB_HOST')];
            $name = self::$dbInfo[strtoupper('DB_NAME')];
            $user = self::$dbInfo[strtoupper('DB_USER')];
            $pass = self::$dbInfo[strtoupper('DB_PASSWORD')];
        }else{
            $host = $this->DB_HOST;
            $name = $this->DB_NAME;
            $user = $this->DB_USER;
            $pass = $this->DB_PASSWORD;
        }
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $pass);
            //@var $HOST setup the database connection

            /** for more security use
             *
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false) rather than
             *
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             * 
             * but you wont have the lovely error messages, or wouldn't know what's wrong as the error could point to many different things,
             *only use this if you are sure everything works fine and you are unlikely to encounter any error, its not advisable to use if you are debugging
            **/
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->success = true;
            //return true if successful
            $this->statusMessage = "<div style='width: 100%; margin: 0 auto; padding: 2%;box-shadow: 0 0 5px #ccc; height: auto; color:#ff4722'><h1>SUCCESSFUL</h1></div>";
        } catch (PDOException $e) {
            $this->statusMessage = "<div style='width: 100%; margin: 0 auto; padding: 2%;box-shadow: 0 0 5px #ccc; height: auto; color:#ff4722'><h1>NOT SUCCESSFUL</h1>";
            echo "<div style='width: 100%; margin: 0 auto; padding: 2%;box-shadow: 0 0 5px #ccc; height: auto; color:#ff4722'><p>" . $error . "</p><br><p>" . $e->getMessage() . "</p></div>";
            $this->success = false;
        }
        return $this->create = $this->pdo;
    }

    // check using html entities
    /**
     * @param $string
     * @return string
     */
    function htmlEntities($string){
        $item = htmlentities($string);
        //TODO encode using html then pass it through the database query
        return $item;
    }

    function isRegistered($email){
        $query = "SELECT * FROM members WHERE email='{$email}'";
        $result = $this->connect()->query($query);
        if($result->rowCount()> 0){
            return true;
        }else{
            return false;
        }
    }

     /*
     * @return Mixed
     * */
    function generateRandomString($length = 10) {
        $characters = '_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * @return mixed
     */
    function quickQuery(){
        //get the number of function arguments
        $numberOfArgs = func_num_args();
        //get all items in function
        $argsList = func_get_args();
        $items = null;
        $itemsForBinding = null;
        // define what each index of an array is
        $statementOptions = array('db' => '','table'=>'','fields'=>'', 'values'=>'');

        //merge define array with user input to have array indexes and values

        $statementItems = array_merge($statementOptions,end($argsList));
        self::$dbInfo = $statementItems['db'];
        $table = $statementItems['table'];
        //fields used for database fields
        $fields = $statementItems['fields'];

        $start = "<div style='width: 100%; margin: 0 auto; padding: 2%;box-shadow: 0 0 5px #ccc; height: auto; color:#ff4722'>";
        $end = "</div>";

        if ($numberOfArgs > 0){
            for($i = 0; $i < $numberOfArgs; $i++){
//              $items .= ":".str_replace(' ','x_AcvSp_x',$argsList[$i]).',';
                if($i > 0)$items .= ":".$this->generateRandomString().',';
                $itemsForBinding .= $argsList[$i]."~";
            }
        }

        //remove the word array from the parameter, it's not needed as it is been passed
        $item_modified = str_replace(',:Array,',"",$items);
        $itemsForBinding_modified = str_replace('Array',"",$itemsForBinding);
        //add a comer to the list to be used as a binding value
        $imploded_Items = implode(', ', explode(',', substr(implode(', ', explode(' ', $item_modified)),0,-1)));
        $itemsForBinding_modified_exploaded =  explode('~', substr($itemsForBinding_modified,0,-2));

        $connect = $this->connect();
        $stmt = $connect->prepare("INSERT INTO $table ($fields)
                            VALUES ($imploded_Items)");

//        array(3) { [0]=> string(6) "ite_m1" [1]=> string(7) "it__em2" [2]=> string(6) "it_em3" }
        $fieldsForBidning = explode(",",(substr(implode("",explode(":",$item_modified)),0,-1)));


        //join both the the fields and value(field will be used as the key and value as the value)
        $combined_arrays = array_combine($fieldsForBidning, $itemsForBinding_modified_exploaded);

        $i = 0;
        $final_combine = '';
        foreach ($combined_arrays as $combined_keys => $combined_values) {
            $final_combine .= "\n"."$".$combined_keys . "='" . $combined_values . "';"."\n";
            $i++;
        }

        $bindPara ="";
        foreach($combined_arrays as $k => $v){
            //bind the values and fields here
            $bindedItem = ":".$k;
            $bindedValue = "$".$k;
            $bindPara .= '$stmt->bindParam('."'$bindedItem'".','. $bindedValue.')'. ";"."\n";;
        }
        $file =  'php/classes/db/db.bindParam.php';

        if (!empty($final_combine)) {
            //open file to write to
            $openFileToWrite = fopen($file, 'w+');
            fwrite($openFileToWrite, "<?php " . "\n".$final_combine."\n\n". $bindPara . " ?>");
            fclose($openFileToWrite);
        }

        if (file_exists($file)) {
            include $file;;
            try {
                $stmt->execute();
                //delete content of the file
                file_put_contents($file, "");
                return true;

            } catch (PDOException $e) {
                // if any error messages, display a nice little message to the user
                echo $start. $e->getMessage() . $end;
            }
        }

    }


    //generate unique random key

    //@return String
    function generateKey(){
        $key = md5($this->email.time());
        return $key;
    }

    // validate input fields

    //@return Boolean
    function validateText($string){
        $remove[] = "'";$remove[] = '"';$remove[] = "-";$remove[] = "_";$remove[] = "@";$remove[] = "/";$remove[] = " ";
        $remove[] = "}";$remove[] = "{";$remove[] = ")";$remove[] = "(";$remove[] = "*";
        $StringReplace= str_replace( $remove, "", $string );
        $htmlEntities = htmlentities($StringReplace);
        $validated = filter_var($htmlEntities, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        return $validated;
    }

    //validate email address;

    //@return Boolean
    function validateEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return true;
        } else {
            return false;
        }
    }

    //@return Boolean
    function ValidateEmailImproved($email){
        $pattern = "/[^a-z0-9_]+/i";
        $email_to_validate = $email;
        if(!preg_match($pattern, $email_to_validate)){
            if(!empty($email)){
                if($this->validateEmail($email_to_validate)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

   // @return String
    function status(){
        return $this->statusMessage;
    }

}

// create a new object of class process
$process = new process();

/**
 * USAGE EXAMPLE
 *
 * $process->status()
 * $unsubscribe_key = $process->generateKey();
 *
 *
 Using default database
 * $process->quickQuery("foo@bar.com","145.814.964.2", 547g7recv24s5fd47sf54s, 2015-11-2015, array('table'=>'subscribers',"fields"=>'email, ip_address, unsubscribe_key, added_on'));
 *
 *
 Specifying a different database
 * $dbInfo = array('DB_HOST'=>'localhost','DB_NAME'=>'user_db','DB_USER'=>'root','DB_PASSWORD'=>'testing');
 * $process->quickQuery("foo@bar.com","145.814.964.2", 547g7recv24s5fd47sf54s, 2015-11-2015, array('db'=>$dbInfo, table'=>'subscribers',"fields"=>'email, ip_address, unsubscribe_key, added_on'));

 */

?>
