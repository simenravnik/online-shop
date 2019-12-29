<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");
    require_once("model/OrderDB.php");
    require_once("ViewHelper.php");

    class OrdersController {

        public static function orderSubmit() {
            echo ViewHelper::render("views/order-submit.php");
        }
        public static function orderConfirmation() {
            echo ViewHelper::render("views/order-confirmation.php");
        }

        public static function get($id) {
            echo ViewHelper::render("views/order-detail.php", OrderDB::get(["id" => $id]));
        }

        public static function index() {
            echo ViewHelper::render("views/order-list.php", [
                "orders" => OrderDB::getAll()
            ]);
        }

        public static function add($values) {
            $data = array_filter($values, "self::getRules");

            if (self::checkValues($data)) {
                $id = OrderDB::insert($data);
                return $id;
            } else {
                echo ViewHelper::redirect(BASE_URL . "orders/" . $id);
            }
        }

        public static function editForm($params) {
            if (is_array($params)) {
                $values = $params;
            } else if (is_numeric($params)) {
                $values = OrderDB::get(["id" => $params]);
            } else {
                throw new InvalidArgumentException("Cannot show form.");
            }

            echo ViewHelper::render("views/order-edit.php", $values);
        }

        public static function edit($id) {
            $data = filter_input_array(INPUT_POST, self::getRules());

            if (self::checkValues($data)) {
                $data["id"] = $id;
                OrderDB::update($data);
                ViewHelper::redirect(BASE_URL . "orders/" . $data["id"]);
            } else {
                self::editForm($data);
            }
        }

        public static function delete($id) {
            $data = filter_input_array(INPUT_POST, [
                'delete_confirmation' => FILTER_REQUIRE_SCALAR
            ]);

            if (self::checkValues($data)) {
                OrderDB::delete(["id" => $id]);
                $url = BASE_URL . "orders";
            } else {
                $url = BASE_URL . "orders/edit/" . $id;
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

            $result = TRUE;
            foreach ($input as $value) {
                $result = $result && $value !== false;
            }

            return $result;
        }

        /**
         * Returns an array of filtering rules for manipulation orders
         * @return type
         */
        public static function getRules() {
            return [
                'id_user' => FILTER_SANITIZE_NUMBER_INT,
                'id_seller' => FILTER_SANITIZE_NUMBER_INT,
                'status' => FILTER_SANITIZE_NUMBER_INT
            ];
        }
    }
?>
