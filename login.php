<?php
    //we write login codes here

    include "DBConnector.php";
    include "user.php";

    $con = new DBConnector;
    if(isset($_POST['btn-login'])){

        $username = $_POST['Username'];
        $password = $_POST['Password'];
        $instance = user::create();
        $instance->setPassword($password);
        $instance->setUsername($username);

        echo $username;
        echo $password;
        $instance->isPasswordCorrect();
       if($instance->isPasswordCorrect()){
            $instance->login();
            //close the databse connection
            $con->closeDatabase();
            //next create a user session
            $instance->createUserSessions();
        }else{
            $con->closeDatabase();
            header("location:login.php");
        }
    }

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/navigation.css">
    <link rel="stylesheet" href="styles/loginStyleSheet.css">
</head>
<body >
    <nav>
        <ul>
            <li><a href="lab1.php">Home</a></li>
        </ul>
    </nav>
    <div class="container">
	<section id="content">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" id="login" >
			<h1>Login Form</h1>
			<div>
				<input type="text"  name="Username" placeholder="Username" required="" id="username" />
			</div>
			<div>
				<input type="password" name="Password" placeholder="Password" required="" id="password" />
			</div>
			<div>
				<input type="submit" name="btn-login" value="Login" />
			</div>
		</form><!-- form -->
	</section><!-- content -->
</div><!-- container -->

</body>
</html>