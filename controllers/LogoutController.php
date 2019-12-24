<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("ViewHelper.php");

    class LogoutController {

        public static function logout() {
            ViewHelper::render("views/logout.php");
        }
}
