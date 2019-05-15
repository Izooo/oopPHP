<?php

include_once 'DBConnector.php';
include_once 'user.php';

   class FileUploader{
        private static $target_directory = "uploads/";
        private static $size_limit = "50000"; /*size given in bytes */
        private $uploadOk = false;
        private $file_original_name;
        private $file_type;
        private $file_size;
        private $final_file_name;
        private $file_tmp;
        private $username;


        public function setUsername($username){
            $this->username = $username;
        }

        public function getUsername(){
            return $this->username;
        }
        /*getters and setters */
        public function setOriginalName($name){
            $this->file_original_name = $name;
        }
        public function getOriginalName(){
           return $this->file_original_name;
        }
        public function setFileTempName($name){
            $this->file_tmp = $name;
        }
        public function getFileTempName(){
           return $this->file_tmp;
        }
        public function setFileType($type){
            $this->file_type = $type;
        }
        public function getFileType(){
            return $this->file_type;
        }
        public function setFileSize($size){
            $this->file_size = $size;
        }
        public function getFileSize(){
            return $this->file_size;
        }
        public function setFinalFileName($final_name){
            $this->final_file_name = $final_name;
        }
        public function getFinalName(){
            return $this->final_file_name;
        }
        /*methods */
        public function uploadFile(){
            $con = new DBConnector;
            if($this->fileWasSelected() && $this->fileTypeIsCorrect() && $this->fileSizeIsCorrect() && $this->fileAlreadyExists()){
                if($this->saveFilePathTo() && $this->moveFile()){
                    $target_file=$this->getFinalName();
                    $uname = $this->getUsername();
                    mysqli_autocommit($con->conn,FALSE);
                    $sql = mysqli_query($con->conn, "UPDATE users SET prof_pic = ('$target_file') WHERE username = '$uname'");
                    mysqli_commit($con->conn);
                    //UPDATE `pics` SET `pic_ID`=[value-1],`pics`=[value-2],`Name`=[value-3] WHERE 1
                    echo "Success";
                    return $sql;
                }
               
                
            }

        }
        public function fileAlreadyExists(){
            if(file_exists($this->getFinalName()))
            {
                echo '<script language="javascript">';
                echo 'alert("File already exists, Please choose an Alternative!")';
                echo '</script>';
                header("Refresh:0");
                die();
            }
            $uploadOk = true;
            return $uploadOk;

        }
        public function saveFilePathTo(){
            
            if($final_file_name = FileUploader::$target_directory . basename($this->getOriginalName())){
                $this->setFinalFileName($final_file_name);
                echo $this->getFinalName(); 
                $uploadOk = true;
                return $uploadOk;
            }
            echo "A problem Occured When Saving the file to the path";
            
        }

        public function moveFile(){
            
            if(move_uploaded_file($this->getFileTempName(),$this->getFinalName()))
            {
                echo "Moving file successfully";
                $uploadOk = true;
                return $uploadOk;
            }
            echo "A problem Occured When Saving the file to the Folder";
            
        }

        public function fileTypeIsCorrect(){
            $tmp = explode('.',$this->getOriginalName());
            $file_ext = end($tmp);
            $extensions= array("jpeg","jpg","png");
            if(in_array($file_ext,$extensions) === false){
                echo '<script language="javascript">';
                echo 'alert("Extension not allowed, please choose a JPEG or PNG file.")';
                echo '</script>';
                header("Refresh:0");
                die();
            }
            $uploadOk = true;
            return $uploadOk;
        }

        public function fileSizeIsCorrect(){

            if($this->getFileSize() > FileUploader::$size_limit) {
                echo '<script language="javascript">';
                echo 'alert("File size must be 50KB or less")';
                echo '</script>';
                header("Refresh:0");
                die();
            }
            $uploadOk = true;
            return $uploadOk;
        }

        public function fileWasSelected(){
            if(empty($this->getOriginalName())){
                echo '<script language="javascript">';
                echo 'alert("User must select a file first!")';
                echo '</script>';
                header("Refresh:0");
                die();
               
                
            }
            $uploadOk = true;
            return $uploadOk;
        }
    }
?>