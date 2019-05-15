<?php
        session_start();
        if(isset($_SESSION['username'])){
            header("Location:private_page.php");
        }
        include_once 'DBConnector.php';
        include_once 'user.php';
        include_once "fileuploader.php";
        $con = new DBConnector;//Database connection is made
        // data isert code starts here

        if(isset($_GET['UptUID']))
        {
            $userUpt = new user(); 
            $UptId = $_GET['UptUID'];
            
            
            $result = $userUpt->update($UptId);
            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) {
                    $id = $row["Id"];
                    $fname = $row["first_name"];
                    $lname = $row["last_name"];
                    $uname = $row["username"];
                    echo $id;
                    echo $fname;
                    echo $lname;
                    echo $uname;
                
                }
            }
        }
        if(isset($_GET['DelUId']))
        {
            $userDel = new user(); 
            $Id = $_GET['DelUId'];
            

            if($userDel->removeOne($Id)){
                echo "The user has been deleted successfully";
            }
        }
        
        if(isset($_POST['btn-save'])){
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $city = $_POST['city_name'];
            $username = $_POST['Username'];
            $password = $_POST['Password'];

            
            $FileUpload = new FileUploader();
            $FileUpload->setUsername($username);
            $file_name = $_FILES['fileToUpload']['name'];
            $FileUpload->setOriginalName($file_name);
            $file_size = $_FILES['fileToUpload']['size'];
            $FileUpload->setFileSize($file_size);
            $file_tmp = $_FILES['fileToUpload']['tmp_name'];
            $FileUpload->setFileTempName($file_tmp);
            $file_type = $_FILES['fileToUpload']['type'];
            $FileUpload->setFileType($file_type);
            
            
            //Creating a users object
            /*Note the way we create our object using constructor that will be used to initializa your variables */
            
            $user = new User($first_name,$last_name,$city,$username,$password);

               
            $utc_timestamp = $_POST['utc_timestamp'];
            $offset = $_POST['time_zone_offset'];
            
           
            $floatTime = floatval($utc_timestamp/1000);
            $intTime = intval($floatTime);
            $cutime = date('F Y-m-d H:i:s',$intTime);
            
            echo $cutime;

            $user->setTimestamp($cutime);
            $user->setOffset($offset);
                       
            

            if(!$user->validateForm()){
                $user->createFormErrorSessions();
                print_r($user->createFormErrorSessions());
                header("Refresh:0");
                die();
            }
            if($user->isUserExist())
            {
                $user->uExistSession();
                print_r($user->uExistSession());
                header("Refresh:0");
                die();
            }else{                
                //$res = $user->save();
                //$Fsave = $FileUpload->uploadFile();  
            }

            /*if($Fsave == true && $res == true){
                echo "Details successfully entered";
            }
            else{
                mysqli_rollback($con->conn);
            }
            

            if($res){
                echo "Save operation was successful";
                echo var_dump($user);
            } else{
                echo "Error ";
                echo mysqli_error($con->conn);
            }*/
            
        }
           
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lab1</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
   <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script type="text/javascript" src="js/validate.js"></script>
    <script type="text/javascript" src="js/timezone.js"></script>
    <link rel="stylesheet" href="styles/navigation.css">
   
</head>
<body>
    <nav>
        <ul>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
   

<div class="container">
    <div class="row">
     <div class="col-sm-12">
        <a id="preview" href="https://dummyimage.com/1000x200/241c24/283247.png&amp;text=Labs" target="_blank">
	        <!--<img src="https://dummyimage.com/1100x150/241c24/283247.png&amp;text=Labs" alt="Pic Not Available">-->
        </a><br><br>
    </div>
    <div class="col-sm-6">

    
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="userDetails" id="formName" enctype = "multipart/form-data" onsubmit="return validateForm()" >
           <div id="form-errors">
                <?php
                    if(!empty($_SESSION['form_errors'])){
                        echo " ". $_SESSION['form_errors'];
                        unset($_SESSION['form_errors']);
                    }
                    if(!empty($_SESSION['userExist'])){
                        echo " ". $_SESSION['userExist'];
                        unset($_SESSION['userExist']);
                    }
                ?>
           </div>
           <br>
            <div class="form-group">
                <input type="text" class="form-control"  name="first_name" required  placeholder="First Name">
            </div>

            <div class="form-group">
                <input type="text" class="form-control"  name="last_name"  placeholder="Last Name">
            </div>

            <div class="form-group">
            <input type="text" class="form-control" name="city_name" placeholder="City">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="Username"  placeholder="username">
            </div>

            <div class="form-group">
            <input type="password" class="form-control" name="Password" placeholder="Password">
            </div>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupFileAddon01">Profile Image</span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload" aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label" for="inputGroupFile01">Upload Image</label>
                </div>
            </div>
            <br>

        
        <button  name = "btn-save"  class="btn btn-primary"><strong>SAVE</strong></button>
        <input type="show" name="utc_timestamp" id="utc_timestamp" value="">
        <input type="show" name="time_zone_offset" id="time_zone_offset" value="">
    </form>
    
    </div>
    
        <div class="col-sm-6">
        <table id="mydatatable" class ="table table-striped" cellspacing="0" width="100%">
        <thead>
            <th>#</th>
            <th>Names</th>
            <th>City</th>
            <th>Username</th>
            <th colspan="3"style="text-align: center;">Action</th>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th>Names</th>
            <th>City</th>
            <th>Username</th>
            <th colspan="3" style="text-align: center;">Action</th>
        </tr>
    </tfoot>
    <tbody>
    
    <?php
    include_once 'user.php';
    $user1 = new User();
    $result = $user1->readAll();
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) {
            echo 
            "
            
                
                        <tr>
                            <td>" . $row["Id"]. "</td>
                            <td>" . $row["first_name"]." ". $row["last_name"]. "</td>
                            <td>" . $row["user_city"]. "</td>
                            <td>" . $row["username"]. "</td>
                            <td><a href='lab1.php?DelUId=" . $row["Id"]. "'><i class='material-icons'>delete</i></a></td>
                            
                        </tr>
                
            ";
           
        }
    } else {
        echo "0 results";
    }
    ?>
    </tbody>
    
    
</table>

        </div>
    </div>
</div>

<!-- Button trigger modal 
<td><button type='button' data-toggle='modal' data-target='#exampleModal'>
                            <a href='lab1.php?UptUID=" . $row["Id"]. "'><i class='material-icons'>update</i></a>
                            </button></td>
-->


<!-- Modal 
<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
-->

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script >
        $(document).ready(function() {
            $('#mydatatable').DataTable({
                "autoWidth": false,
                
                "pageLength": 5
            });
        } );
</script>

</body>
</html>