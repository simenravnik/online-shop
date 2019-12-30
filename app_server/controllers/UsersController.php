<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("app_server/model/UserDB.php");
    require_once("ViewHelper.php");

    class UsersController {
        public static function getUserDetails($id) {
            $userDetails = UserDB::get(["id" => $id]);
            return $userDetails;
        }

        public static function get($id) {
            echo ViewHelper::render("app_server/views/user-detail.php", UserDB::get(["id" => $id]));
        }

        public static function index() {
            echo ViewHelper::render("app_server/views/user-list.php", [
                "users" => UserDB::getAll()
            ]);
        }

        public static function addForm($values = [
            "name" => "",
            "lastName" => "",
            "email" => "",
            "password" => "",
            "type" => 0,
            "address" => "",
            "phone" => "",
            "zipcode_id" => 1,
            "activated" => 0
        ]) {
            echo ViewHelper::render("app_server/views/user-add.php", $values);
        }

        public static function add() {
            $data = filter_input_array(INPUT_POST, self::getRules());

            if (isset($data["password"])) {
                $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            }

            if (!isset($data["activated"]) || $data["activated"] === "" || $data["activated"] == null) {
                $data["activated"] = 0;
            }
            //print_r("Some data: ");
            //print_r($data);
            if (self::checkValues($data)) {
                $id = UserDB::insert($data);
                echo ViewHelper::redirect(BASE_URL . "users/" . $id);
            } else {
                self::addForm($data);
            }
        }

        public static function editForm($params) {
            if (is_array($params)) {
                $values = $params;
            } else if (is_numeric($params)) {
                $values = UserDB::get(["id" => $params]);
            } else {
                throw new InvalidArgumentException("Cannot show form.");
            }

            echo ViewHelper::render("app_server/views/user-edit.php", $values);
        }

        public static function edit($id) {
            $data = filter_input_array(INPUT_POST, self::getRules());

            if (isset($data["password"]) && strlen($data["password"]) < 25) {
                $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            }

            if (!isset($data["activated"]) || $data["activated"] === "" || $data["activated"] == null || $data["activated"] == false) {
                $data["activated"] = 0;
            }

            if (self::checkValues($data)) {
                $data["id"] = $id;
                UserDB::update($data);
                if($_SESSION["type"] == 0 || $_SESSION["type"] == 1) {
                    ViewHelper::redirect(BASE_URL . "users/" . $data["id"]);
                } else {
                    ViewHelper::redirect(BASE_URL . "profile/" . $data["id"]);
                }
            } else {
                self::editForm($data);
            }
        }

        public static function editProfileForm($params) {
            if (is_array($params)) {
                $values = $params;
            } else if (is_numeric($params)) {
                $values = UserDB::get(["id" => $params]);
            } else {
                throw new InvalidArgumentException("Cannot show form.");
            }

            echo ViewHelper::render("app_server/views/profile-edit.php", $values);
        }

        public static function editProfile($id) {
            $data = filter_input_array(INPUT_POST, self::getRules());

            if (isset($data["password"])) {
                $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            }

            if (!isset($data["activated"]) || $data["activated"] === "" || $data["activated"] == null) {
                $data["activated"] = 0;
            }

            if (self::checkValues($data)) {
                $data["id"] = $id;
                UserDB::update($data);
                if($_SESSION["type"] == 0 || $_SESSION["type"] == 1) {
                    ViewHelper::redirect(BASE_URL . "users/" . $data["id"]);
                } else {
                    ViewHelper::redirect(BASE_URL . "profile/" . $data["id"]);
                }
            } else {
                self::editProfileForm($data);
            }
        }

        public static function delete($id) {
            $data = filter_input_array(INPUT_POST, [
                'delete_confirmation' => FILTER_REQUIRE_SCALAR
            ]);

            if (self::checkValues($data)) {
                UserDB::delete(["id" => $id]);
                $url = BASE_URL . "users";
            } else {
                $url = BASE_URL . "users/edit/" . $id;
            }

            ViewHelper::redirect($url);
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
