<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
      
    include 'crud.php';
    include 'Authenticator.php';
    include_once 'DBConnector.php';
    
    class User implements crud,Authenticator{

        private $user_id;
        private $first_name;
        private $last_name;
        private $city_name;
        private $username;
        private $password;
       
        private $timestamp;
        private $offset;
      

        /*We can use this class constructor to initializ our values
        members variables cannot be instantiated from elsewhere; They private */
        
        function __construct($first_name = "",$last_name ="",$city_name="",$username="",$password=""){
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->city_name = $city_name;
            $this->username = $username;
            $this->password = $password;
        }
        
        /*PHP does not allow multiple constructors, so lets fake one.
        Because when we login, we do not have all the details 
        we only have the username and we still need to use this same class.
        we make this method static so that we access it with the
        class rather than an object
         */

        /**
         * Static constructor
         */
        public static function create(){
            $instance = new self();
            return $instance;
        }

        public function setTimestamp($timestamp){
            $this->timestamp = $timestamp;
        }

        public function getTimestamp(){
            return $this->timestamp;
        }
        public function setOffset($offset){
            $this->offset = $offset;
        }

        public function getOffset(){
            return $this->offset;
        }
       

        public function setUsername($username){
            $this->username = $username;
        }

        public function getUsername(){
            return $this->username;
        }

        public function setPassword($password){
            $this->password = $password;
        }

        public function getPassword(){
            return $this->password;
        }
        
        //user_id setter
        public function setUserId($user_id){
            $this->user_id = $user_id;
        }

        //user_id Getter
        public function getUserId(){
            return $this->$user_id;
        }
        /*Because we implemented the 'Crud' interface, we have
        to define all the methods, otherwise we will run into an error */
        
        public function save(){
            $fn = $this->first_name;
            $ln = $this->last_name;
            $city = $this->city_name;
            $uname = $this->username;
            $this->hashPassword();
            $pass = $this->password;
            
            $time_stamp = $this->getTimestamp();
            $off_set = $this->getOffset();

            $con = new DBConnector;//Database connection is made
            $mysql = "INSERT INTO users (first_name,last_name,user_city,username,pass,time_stamp,offset) VALUES('$fn','$ln','$city','$uname','$pass','$time_stamp','$off_set')";
            
            $res = mysqli_query($con->conn,"INSERT INTO users (first_name,last_name,user_city,username,pass,time_stamp,offset) VALUES('$fn','$ln','$city','$uname','$pass','$time_stamp','$off_set')") or die("Error!".mysqli_error());
            return $res;
        }

        public function readAll(){
            $con = new DBConnector;//Database connection is made
            $read =  mysqli_query($con->conn, "SELECT * FROM users") or die("Error:".mysqli_error());
            if($read)
            {
               // echo "Success";
                //echo mysqli_error($con->conn);
            }
            return $read;
            
        }
        public function readUnique(){
            return null;
        }
        public function search(){
            return null;
        }
        public function update($UptID){
            $con = new DBConnector;
            $read =  mysqli_query($con->conn, "SELECT * FROM users WHERE Id = '$UptID'") or die("Error:".mysqli_error());
            if($read)
            {
               // echo "Success";
                //echo mysqli_error($con->conn);
            }
            return $read;
            return null;
        }
        public function removeOne($UserID){
            echo $UserID;
            $con = new DBConnector;
            $result = mysqli_query($con->conn,"DELETE FROM `users` WHERE Id = '$UserID'");

            return $result;
           
        }
        public function removeAll(){
            return null;
        }

        public function validateForm(){
            $fn = $this->first_name;
            $ln = $this->last_name;
            $city = $this->city_name;

            if($fn == "" || $ln == "" || $city == ""){
                return false;
            }
            return true;
        }

        public function createFormErrorSessions(){
            $fn = $this->first_name;
            $ln = $this->last_name;
            $city = $this->city_name;
            if($fn == "")
            {
                $_SESSION['form_errors'] = "First Name field is empty";
            } elseif($ln == "")
            {
                $_SESSION['form_errors'] = "Last Name field is empty";
            }
            else
            {
                $_SESSION['form_errors'] = "City field is empty";
            }
            
        }
        public function hashPassword(){
           $this->password = password_hash($this->password,PASSWORD_DEFAULT);
        }
        public function isPasswordCorrect(){   
            $con = new DBConnector;
            $found = false;
            $uname = $this->getUsername();
            $res = mysqli_query($con->conn,"SELECT * FROM users WHERE username = '$uname'") or die("Error: ".mysqli_error());
            print_r($res);
            while($row=mysqli_fetch_array($res)){
                if(password_verify($this->getPassword(), $row['pass']) && $this->getUsername() == $row['username']){
                    $found = true;
                }
            }
            $con->closeDatabase();
            return $found;
        }
        public function login(){
           if($this->isPasswordCorrect()){
               //password is correct so we load the protected page
               header("Location: private_page.php");
           }
        }
        public function logout(){
            unset($_SESSION['username']);
            session_destroy();
            header("Location: lab1.php");
        }
        public function createUserSessions(){
            $_SESSION['username'] = $this->getUsername();
        }
        public function isUserExist(){
            $con = new DBConnector;
            $uname = $this->username;
            $res = mysqli_query($con->conn,"SELECT * FROM users WHERE username = '$uname'") or die("Error: ".mysqli_error());
            if($res->num_rows > 0){
                return true;
            }else{
                return false;
            }
        }
        public function uExistSession(){
            $_SESSION['userExist'] = "Sadly, Username already taken. Please choose another one";
        }
    }
?>