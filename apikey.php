<?php
session_start();
include_once 'DBConnector.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    //we do not allw users to visit this page via a url
    header('HTTP/1.0 403 Forbidden');
    echo 'You are forbidden!';
} else {
    $api_key = null;
    $api_key = generatedApiKey(64);//We generate a key 64 characters long
    header('Content-type: application/json');
    //our response if a json one
    echo generateResponse($api_key);
    //print_r($api_key);
    //die();
}
/*this is how we generate a key. But you can device your own method
the parameter str_legnth determines the length of the key you want.
In our case we have choosen 64 characters*/


function generatedApiKey($str_length){
    //base 62 map
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    //get enough random bits for base 64 encoding (and prevent '=' padding)
    //note: +1 is faster than ceil()
    $bytes = openssl_random_pseudo_bytes(3*$str_length/4+1);
    //convert base 64 to base 62 by mapping + and / to something from the base 62 map
    //use the first 2 random bytes for the new characters
    $repl = unpack('C2', $bytes);

    $first  = $chars[$repl[1]%62];
    $second = $chars[$repl[2]%62];
    return strtr(substr(base64_encode($bytes), 0, $str_length), '+/', "$first$second");
}

function saveApiKey(){
    /*Write the code that will save the api key for the user
    this function returns true if the key is saved, false otherwise*/
    $con = new DBConnector;
    $username = $_SESSION['username'];

    $res = mysqli_query($con->conn, "SELECT Id FROM users WHERE username = '$username'");
    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
        $UserID = $row['Id'];
        }
    }

    $final_api_key = generatedApiKey(64);
    $result = mysqli_query($con->conn, "INSERT INTO `api_keys`(`user_id`, `api_key`) VALUES ('$UserID','$final_api_key')");
    if($result){
        return true;
    }
    
}

function generateResponse($api_key){
    if(saveApiKey()){
        $res = ['success' => 1, 'message' => $api_key];
    } else {
        $res = ['success' => 0, 'message' => 'Something went wrong. Please regenerate the API key'];
    }
    return json_encode($res);
}
?>