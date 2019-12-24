<?php

    session_start();

    # PRODUCTS CONTROLLERS
    require_once("controllers/ProductsController.php");
    require_once("controllers/ProductsControllerREST.php");

    # REGISTRATION AND LOGIN CONTROLLERS
    require_once("controllers/LoginController.php");
    require_once("controllers/LogoutController.php");
    require_once("controllers/RegistrationController.php");

    # EDITING USERS
    require_once("controllers/UsersController.php");

    define("BASE_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php"));

    define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/images/");
    define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/");

    $path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

    $urls = [
        # PRODUCTS
        "/^products$/" => function ($method) {
            ProductsController::index();
        },
        "/^products\/(\d+)$/" => function ($method, $id) {
            ProductsController::get($id);
        },
        "/^products\/add$/" => function ($method) {
            if ($method == "POST") {
                ProductsController::add();
            } else {
                ProductsController::addForm();
            }
        },
        "/^products\/edit\/(\d+)$/" => function ($method, $id) {
            if ($method == "POST") {
                ProductsController::edit($id);
            } else {
                ProductsController::editForm($id);
            }
        },
        "/^products\/delete\/(\d+)$/" => function ($method, $id) {
            if ($method == "POST") {
                ProductsController::delete($id);
            }
        },
        "/^$/" => function () {
            ViewHelper::redirect(BASE_URL . "products");
        },
        # REGISTRATION AND LOGIN
        "/^logout$/" => function ($method) {
            LogoutController::logout();
        },
        "/^login$/" => function ($method) {
            LoginController::index();
        },
        "/^registration$/" => function ($method) {
            RegistrationController::indexReg();
        },
        # LOGIN WITH CERTIFICATE
        "/^certificate$/" => function ($method) {
            LoginController::certificateAuth();
        },
        # EDITING PROFILE
        "/^profile\/(\d+)$/" => function ($method, $id) {
            if ($method == "POST") {
                UsersController::editProfile($id);
            } else {
                UsersController::editProfileForm($id);
            }
        },
        # EDITING USERS
        "/^users$/" => function ($method) {
            UsersController::index();
        },
        "/^users\/(\d+)$/" => function ($method, $id) {
            UsersController::get($id);
        },
        "/^users\/add$/" => function ($method) {
            if ($method == "POST") {
                UsersController::add();
            } else {
                UsersController::addForm();
            }
        },
        "/^users\/edit\/(\d+)$/" => function ($method, $id) {
            if ($method == "POST") {
                UsersController::edit($id);
            } else {
                UsersController::editForm($id);
            }
        },
        "/^users\/delete\/(\d+)$/" => function ($method, $id) {
            if ($method == "POST") {
                UsersController::delete($id);
            }
        },
        # REST API
        "/^api\/products\/(\d+)$/" => function ($method, $id) {
            // TODO: izbris knjige z uporabo HTTP metode DELETE
            switch ($method) {
                case "DELETE":
                    ProductsControllerREST::delete($id);
                    break;
                case "PUT":
                    ProductsControllerREST::edit($id);
                    break;
                default: # GET
                    ProductsControllerREST::get($id);
                    break;
            }
        },
        "/^api\/products$/" => function ($method) {
            switch ($method) {
                case "POST":
                    ProductsControllerREST::add();
                    break;
                default: # GET
                    ProductsControllerREST::index();
                    break;
            }
        },
    ];

    foreach ($urls as $pattern => $controller) {
        if (preg_match($pattern, $path, $params)) {
            try {
                $params[0] = $_SERVER["REQUEST_METHOD"];
                $controller(...$params);
            } catch (InvalidArgumentException $e) {
                ViewHelper::error404();
            } catch (Exception $e) {
                ViewHelper::displayError($e, true);
            }

            exit();
        }
    }

    ViewHelper::displayError(new InvalidArgumentException("No controller matched."), true);
?>
