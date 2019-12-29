<?php

//require_once 'model/AbstractDB.php';

class OrderProductDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO order_product (id_order, id_product, amount) "
                        . " VALUES (:id_order, :id_product, :amount)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE order_product SET id_order = :id_order, "
                        . "id_product = :id_product, amount = :amount"
                        . " WHERE id_order = :id_order AND id_product = :id_product", $params);
    }

    public static function delete(array $id_order) {
        return parent::modify("DELETE FROM order_product WHERE id_order = :id_order", $id_order);
    }

    public static function get(array $id_order) {
        $order_products = parent::query("SELECT id_order, id_product, amount"
                        . " FROM order_product"
                        . " WHERE id_order = :id_order", $id_order);

        return $order_products;
    }

    public static function getAll() {
        return parent::query("SELECT id_order, id_product, amount"
                        . " FROM order_product"
                        . " ORDER BY id_order ASC");
    }

    public static function getAllwithURI(array $prefix) {
        return parent::query("SELECT id_order, id_product, amount"
                        . "          CONCAT(:prefix, id_order) as uri "
                        . "FROM order_product "
                        . "ORDER BY id_order ASC", $prefix);
    }
}
