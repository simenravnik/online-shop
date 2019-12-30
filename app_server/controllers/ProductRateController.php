<?php
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("X-XSS-Protection: 1; mode=block");

    require_once("app_server/model/ProductRateDB.php");
    require_once("ViewHelper.php");

    class ProductRateController {

        public static function getProductRateById($id) {
            return ProductRateDB::get(["id_product" => $id]);
        }

        public static function add($values) {
            $data = array_filter($values, "self::getRules");

            if (self::checkValues($data)) {
                $id = ProductRateDB::insert($data);
            } else {
                echo ViewHelper::redirect(BASE_URL . "products");
            }
        }

        public static function edit($values) {
            $data = array_filter($values, "self::getRules");

            if (self::checkValues($data)) {
                ProductRateDB::update($data);
            } else {
                echo ViewHelper::redirect(BASE_URL . "products");
            }
        }

        public static function delete($id) {
            $data = filter_input_array(INPUT_POST, [
                'delete_confirmation' => FILTER_REQUIRE_SCALAR
            ]);

            if (self::checkValues($data)) {
                ProductRateDB::delete(["id" => $id]);
                $url = BASE_URL . "products";
            } else {
                $url = BASE_URL . "products/details/" . $id;
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
                'id_product' => FILTER_SANITIZE_NUMBER_INT,
                'num_ratings' => FILTER_SANITIZE_NUMBER_INT,
                'rating' => FILTER_SANITIZE_NUMBER_INT
            ];
        }
    }
?>
