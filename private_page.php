<?php
  session_start();
include_once 'DBConnector.php';
include_once 'user.php';
$con = new DBConnector;
  
    if(!isset($_SESSION['username'])){
        header("Location:login.php");
    }
    
    $username = $_SESSION['username'];
    $res = mysqli_query($con->conn, "SELECT prof_pic,Id FROM users WHERE username = '$username'");
    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
           $img = $row['prof_pic'];
           $UserId = $row['Id'];
        }
     }

     function fetchUserApiKey(){

        $con = new DBConnector;
        $username = $_SESSION['username'];
        $res = mysqli_query($con->conn, "SELECT prof_pic,Id FROM users WHERE username = '$username'");
        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()){
               $img = $row['prof_pic'];
               $UserId = $row['Id'];
            }
         }
   
        $result = mysqli_query($con->conn, "SELECT api_key FROM api_keys WHERE user_id = '$UserId'");
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
               $api_key = $row['api_key'];
               
            }
         }
         echo $api_key;
         //return $api_key;
         
     }
     

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=ZCOOL+QingKe+HuangYou" rel="stylesheet">
    <link rel="stylesheet" href="styles/navigation.css">

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
     <script type="text/javascript" src="js/validate.js"></script>
     <script type="text/javascript" src="js/apikey.js"></script>
     <!--<link rel="stylesheet" type="text/css" href="styles/validate.css">
     Bootstrap file-->
     <!--js-->

     <title>Private Page</title>
     
     
</head>
<body>
    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <img src="<?php echo $img?>" alt="Pic not availbale">
        <p>This is a Private Page!</p>
        <p>Authorized Access Only</p>
        <p>We want to protect it</p>

    <hr>
    <h3>Here, we will create an API that allo users/Developer to order items from external systems</h3>
    <hr>
    <h4>We now put this feature of alowing users to generate an API key. Click the button to generate the API Key</h4>

    <button class="btn btn-primary" name="api-key-btn" id="api-key-btn"> Generate API Key</button><br><br>
    <!--This text area will hold the API Key-->
    <strong>Your API Key:</strong>(Not that if your API Key Key is already in use by already running applications, generating a new key will stop the application from functioning) <br>
    <textarea name="api_key" id="" cols="100" rows="2" id="api_key" readonly><?php echo fetchUserApiKey();?></textarea>

    <h3>Service description</h3>
    We have a service/API that allows external applications to order food and also 
    pull all order status by using order id. Let's do it.
    <hr>
        
    
</body>
</html>