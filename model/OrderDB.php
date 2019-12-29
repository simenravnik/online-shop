<?php

//require_once 'model/AbstractDB.php';

class OrderDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO shop_order (id_user, id_seller, status) "
                        . " VALUES (:id_user, :id_seller, :status)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE shop_order SET id_user = :id_user, "
                        . "id_seller = :id_seller, status = :status"
                        . " WHERE id = :id", $params);
    }

    public static function delete(array $id) {
        return parent::modify("DELETE FROM shop_order WHERE id = :id", $id);
    }

    public static function get(array $id) {
        $shop_orders = parent::query("SELECT id, id_user, id_seller, status"
                        . " FROM shop_order"
                        . " WHERE id = :id", $id);

        if (count($shop_orders) == 1) {
            return $shop_orders[0];
        } else {
            throw new InvalidArgumentException("No such shop_order");
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, id_user, id_seller, status"
                        . " FROM shop_order"
                        . " ORDER BY id ASC");
    }

    public static function getAllwithURI(array $prefix) {
        return parent::query("SELECT id, id_user, id_seller, status"
                        . "          CONCAT(:prefix, id) as uri "
                        . "FROM shop_order "
                        . "ORDER BY id ASC", $prefix);
    }
}
