<?php

//require_once 'model/AbstractDB.php';

class OrderDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO order (id_user, id_seller, status) "
                        . " VALUES (:id_user, :id_seller, :status)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE order SET id_user = :id_user, "
                        . "id_seller = :id_seller, status = :status"
                        . " WHERE id = :id", $params);
    }

    public static function delete(array $id) {
        return parent::modify("DELETE FROM order WHERE id = :id", $id);
    }

    public static function get(array $id) {
        $orders = parent::query("SELECT id, id_user, id_seller, status"
                        . " FROM order"
                        . " WHERE id = :id", $id);

        if (count($orders) == 1) {
            return $orders[0];
        } else {
            throw new InvalidArgumentException("No such order");
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, id_user, id_seller, status"
                        . " FROM order"
                        . " ORDER BY id ASC");
    }

    public static function getAllwithURI(array $prefix) {
        return parent::query("SELECT id, id_user, id_seller, status"
                        . "          CONCAT(:prefix, id) as uri "
                        . "FROM order "
                        . "ORDER BY id ASC", $prefix);
    }
}
