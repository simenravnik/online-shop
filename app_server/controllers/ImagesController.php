<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("ViewHelper.php");

        class ImagesController {

            public static function index() {
                $values = [
                   "id_product" => 1
                ];
                echo ViewHelper::render("app_server/views/upload-image.php", $values);
            }

        }
?>
