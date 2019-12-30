<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("app_server/model/OrderProductDB.php");
    require_once("ViewHelper.php");

    class OrderProductsController {

        public static function getOrderProductsById($id) {
            return OrderProductDB::get(["id_order" => $id]);
        }

        public static function add($values) {
            $data = array_filter($values, "self::getRules");

            if (self::checkValues($data)) {
                $id = OrderProductDB::insert($data);
            } else {
                echo ViewHelper::redirect(BASE_URL . "products");
            }
        }

        public static function delete($id) {
            $data = filter_input_array(INPUT_POST, [
                'delete_confirmation' => FILTER_REQUIRE_SCALAR
            ]);

            if (self::checkValues($data)) {
                OrderProductDB::delete(["id" => $id]);
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
                'id_order' => FILTER_SANITIZE_NUMBER_INT,
                'id_product' => FILTER_SANITIZE_NUMBER_INT,
                'amount' => FILTER_SANITIZE_NUMBER_INT
            ];
        }
    }
?>
