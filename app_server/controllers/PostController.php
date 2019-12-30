<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("app_server/model/PostDB.php");
    require_once("ViewHelper.php");

    class PostController {

        public static function get($id) {
            return PostDB::get(["id" => $id]);
        }
    }
?>
