<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("ViewHelper.php");

    class RegistrationController {

        public static function indexReg() {
            echo ViewHelper::render("views/registration.php");
        }

        public static function login() {
            header("location:". BASE_URL . "login");
            // echo ViewHelper::render("view/Login.php");
        }
    }
?>
