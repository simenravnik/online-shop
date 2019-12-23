<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");
    require_once("model/ProductDB.php");
    require_once("ViewHelper.php");

    class ProductsController {
        public static function getProductDetails($id) {
            return ProductDB::get(["id" => $id]);
        }

        public static function get($id) {
            echo ViewHelper::render("views/product-detail.php", ProductDB::get(["id" => $id]));
        }

        public static function index() {
            echo ViewHelper::render("views/product-list.php", [
                "products" => ProductDB::getAll()
            ]);
        }

        public static function addForm($values = [
            "title" => "",
            "price" => "",
            "activated" => 0,
            "description" => ""
        ]) {
            echo ViewHelper::render("views/product-add.php", $values);
        }

        public static function add() {
            $data = filter_input_array(INPUT_POST, self::getRules());

            if (!isset($data["activated"]) || $data["activated"] === "" || $data["activated"] == null) {
                $data["activated"] = 0;
            }

            if (self::checkValues($data)) {
                $id = ProductDB::insert($data);
                echo ViewHelper::redirect(BASE_URL . "products/" . $id);
            } else {
                self::addForm($data);
            }
        }

        public static function editForm($params) {
            if (is_array($params)) {
                $values = $params;
            } else if (is_numeric($params)) {
                $values = ProductDB::get(["id" => $params]);
            } else {
                throw new InvalidArgumentException("Cannot show form.");
            }

            echo ViewHelper::render("views/product-edit.php", $values);
        }

        public static function edit($id) {
            $data = filter_input_array(INPUT_POST, self::getRules());

            if (!isset($data["activated"]) || $data["activated"] === "" || $data["activated"] == null) {
                $data["activated"] = 0;
            }

            if (self::checkValues($data)) {
                $data["id"] = $id;
                ProductDB::update($data);
                ViewHelper::redirect(BASE_URL . "products/" . $data["id"]);
            } else {
                self::editForm($data);
            }
        }

        public static function delete($id) {
            $data = filter_input_array(INPUT_POST, [
                'delete_confirmation' => FILTER_REQUIRE_SCALAR
            ]);

            if (self::checkValues($data)) {
                ProductDB::delete(["id" => $id]);
                $url = BASE_URL . "products";
            } else {
                $url = BASE_URL . "products/edit/" . $id;
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

            print_r($input);
            $result = TRUE;
            foreach ($input as $value) {
                $result = $result && $value !== false;
            }

            return $result;
        }

        /**
         * Returns an array of filtering rules for manipulation products
         * @return type
         */
        public static function getRules() {
            return [
                'title' => FILTER_SANITIZE_SPECIAL_CHARS,
                'description' => FILTER_SANITIZE_SPECIAL_CHARS,
                'price' => FILTER_VALIDATE_FLOAT,
                'activated' => FILTER_VALIDATE_BOOLEAN
            ];
        }
    }
?>
