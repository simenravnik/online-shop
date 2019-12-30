<?php

    session_start();

    # API
    require_once("app_api/ProductsControllerREST.php");
    require_once("app_api/AndroidREST.php");
    require_once("app_api/UsersControllerREST.php");

    # PRODUCTS CONTROLLERS
    require_once("app_server/controllers/ProductsController.php");

    # REGISTRATION AND LOGIN CONTROLLERS
    require_once("app_server/controllers/LoginController.php");
    require_once("app_server/controllers/LogoutController.php");
    require_once("app_server/controllers/RegistrationController.php");

    # EDITING USERS
    require_once("app_server/controllers/UsersController.php");

    # ORDERS
    require_once("app_server/controllers/OrdersController.php");
    require_once("app_server/controllers/OrderProductsController.php");

    # POST OFFICE
    require_once("app_server/controllers/PostController.php");

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
        # ORDERS
        "/^orders\/edit\/(\d+)$/" => function ($method, $id) {
            if ($method == "POST") {
                OrdersController::edit($id);
            }
        },
        "/^orders\/delete\/(\d+)$/" => function ($method, $id) {
            OrdersController::delete($id);
        },
        "/^orders\/submit$/" => function ($method) {
            OrdersController::orderSubmit();
        },
        "/^orders\/confirmation$/" => function ($method) {
            OrdersController::orderConfirmation();
        },
        "/^orders$/" => function ($method) {
            OrdersController::index();
        },
        "/^orders\/(\d+)$/" => function ($method, $id) {
            OrdersController::get($id);
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
        # USERS REST API
        "/^api\/users\/(\d+)$/" => function ($method, $id) {
            switch ($method) {
               case "PUT":
                   UsersControllerREST::edit($id);
                   break;
               case "DELETE":
                   UsersControllerREST::delete($id);
                   break;
               default: # GET
                   UsersControllerREST::getUserDetails($id);
                   break;
            }
        },
        "/^api\/users$/" => function ($method) {
            switch ($method) {
               case "POST":
                   UsersControllerREST::add();
                   break;
               default: # GET
                   UsersControllerREST::index();
                   break;
            }
        },

        # ANDROID REST API
        "/^api\/login$/" => function ($method) {
            switch ($method) {
                case "POST":
                    AndroidREST::login();
                    break;
                default: # GET
                    AndroidREST::login();
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
