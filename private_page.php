<?php
  session_start();
include_once 'DBConnector.php';
include_once 'user.php';
$con = new DBConnector;
  
    if(!isset($_SESSION['username'])){
        header("Location:login.php");
    }
    
    $username = $_SESSION['username'];
    $res = mysqli_query($con->conn, "SELECT prof_pic FROM users WHERE username = '$username'");
    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
           $img = $row['prof_pic'];
        }
     }

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=ZCOOL+QingKe+HuangYou" rel="stylesheet">
    <title>Private Page</title>
    <link rel="stylesheet" href="styles/navigation.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
        
    
</body>
</html>