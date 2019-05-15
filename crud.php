<?php
    
    //We create
    interface Crud{
        /*All these methods have to be implemented by any
         class that implemets these interface */

         public function save();
         public function readAll();
         public function readUnique();
         public function search();
         public function update($UptID);
         public function removeOne($UserID);
         public function removeAll();

         public function validateForm();
         public function createFormErrorSessions();
    }
?>