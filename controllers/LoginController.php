<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("ViewHelper.php");
    require_once("model/ProductDB.php");

        class LoginController {
            public static function certificateAuth() {
                echo ViewHelper::render("views/certificate.php");
            }

            public static function index() {
                echo ViewHelper::render("views/login.php");
            }

            public static function logged_in() {
                if($_SESSION["type"] == 0) {
                    header("location:". BASE_URL . "users");
                } else {
                    header("location:". BASE_URL . "products");
                }
            }
        }
?>
