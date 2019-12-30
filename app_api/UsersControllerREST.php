<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("app_server/model/UserDB.php");
    require_once("ViewHelper.php");

    class UsersControllerREST {

        public static function index() {
            try {
                echo ViewHelper::renderJSON(UserDB::getAll());
            } catch (InvalidArgumentException $e) {
                echo ViewHelper::renderJSON($e->getMessage(), 404);
            }
        }

        public static function getUserDetails($id) {
            try {
                echo ViewHelper::renderJSON(UserDB::get(["id" => $id]));
            } catch (InvalidArgumentException $e) {
                echo ViewHelper::renderJSON($e->getMessage(), 404);
            }
        }

        public static function add() {
            $data = json_decode(file_get_contents("php://input"), true);

            if (isset($data["password"])) {
                $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            }

            if (!isset($data["activated"]) || $data["activated"] === "" || $data["activated"] == null) {
                $data["activated"] = 0;
            }

            if (self::checkValues($data)) {
                $id = UserDB::insert($data);
                echo ViewHelper::renderJSON("", 201);
                ViewHelper::redirect(BASE_URL . "api/users/$id");
            } else {
                echo ViewHelper::renderJSON("Missing data.", 400);
            }
        }

        public static function edit($id) {
     
            // spremenljivka $_PUT ne obstaja, zato jo moremo narediti sami
            $_PUT = [];
            parse_str(file_get_contents("php://input"), $_PUT);
            $data = filter_var_array($_PUT, UsersControllerREST::getRules());
            #$data = json_decode(file_get_contents("php://input"), true);

            if (ProductsController::checkValues($data)) {
                $data["id"] = $id;
                UserDB::update($data);
                echo ViewHelper::renderJSON("", 201);
            } else {
                echo ViewHelper::renderJSON("Missing data.", 400);
            }
        }

        public static function delete($id) {
            try {
                UserDB::get(["id" => $id]);
                UserDB::delete(["id" => $id]);
                echo ViewHelper::renderJSON("", 200);
            } catch (Exception $ex) {
                // Vrni kodo 404 v primeru neobstoječe knjige
                echo ViewHelper::renderJSON("No such user", 404);
                }
        }

        /**
         * Returns TRUE if given $input array contains no FALSE values
         * @param type $input
         * @return type
         */
        public static function checkValues($input) {
            if (empty($input)) {
                return FALSE;
            }

            //print_r($input);
            $result = TRUE;
            foreach ($input as $value) {
                $result = $result && $value !== false;
            }

            return $result;
        }

        /**
         * Returns an array of filtering rules for manipulation users
         * @return type
         */
        public static function getRules() {
            return [
                'name' => FILTER_SANITIZE_SPECIAL_CHARS,
                'lastName' => FILTER_SANITIZE_SPECIAL_CHARS,
                'email' => FILTER_SANITIZE_SPECIAL_CHARS,
                'password' => FILTER_SANITIZE_SPECIAL_CHARS,
                'type' => FILTER_SANITIZE_NUMBER_INT,
                'address' => FILTER_SANITIZE_SPECIAL_CHARS,
                'zipcode_id' => FILTER_SANITIZE_NUMBER_INT,
                'phone' => FILTER_SANITIZE_SPECIAL_CHARS,
                'activated' => FILTER_VALIDATE_BOOLEAN
            ];
        }

    }
?>
