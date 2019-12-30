<?php
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header("X-XSS-Protection: 1; mode=block");

require_once("model/ProductDB.php");
require_once("controllers/ProductsController.php");
require_once("ViewHelper.php");

class ProductsControllerREST {

    public static function get($id) {
        try {
            echo ViewHelper::renderJSON(ProductDB::get(["id" => $id]));
        } catch (InvalidArgumentException $e) {
            echo ViewHelper::renderJSON($e->getMessage(), 404);
        }
    }

    public static function index() {
        $prefix = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]
                . $_SERVER["REQUEST_URI"];
        echo ViewHelper::renderJSON(ProductDB::getAllwithURI(["prefix" => $prefix]));
    }

    public static function add() {
        $data = filter_input_array(INPUT_POST, ProductsController::getRules());

        if (ProductsController::checkValues($data)) {
            $id = ProductDB::insert($data);
            echo ViewHelper::renderJSON("", 201);
            ViewHelper::redirect(BASE_URL . "api/products/$id");
        } else {
            echo ViewHelper::renderJSON("Missing data.", 400);
        }
    }

    public static function edit($id) {
        // spremenljivka $_PUT ne obstaja, zato jo moremo narediti sami
        $_PUT = [];
        parse_str(file_get_contents("php://input"), $_PUT);
        $data = filter_var_array($_PUT, ProductsController::getRules());

        if (ProductsController::checkValues($data)) {
            $data["id"] = $id;
            ProductDB::update($data);
            echo ViewHelper::renderJSON("", 200);
        } else {
            echo ViewHelper::renderJSON("Missing data.", 400);
        }
    }

    public static function delete($id) {
        try {
            ProductDB::get(["id" => $id]);
            ProductDB::delete(["id" => $id]);
            echo ViewHelper::renderJSON("", 200);
        } catch (Exception $ex) {
            // Vrni kodo 404 v primeru neobstoječe knjige
            echo ViewHelper::renderJSON("No such product", 404);
            }
        }
}
