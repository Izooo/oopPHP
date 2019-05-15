<?php
    interface Authenticator{
        public function hashPassword();
        public function isPasswordCorrect();
        public function login();
        public function logout();
        public function createUserSessions();
        public function isUserExist();
        public function uExistSession();

    }
?>