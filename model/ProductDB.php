<?php

require_once 'model/AbstractDB.php';

class ProductDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO product (title, description, price, activated) "
                        . " VALUES (:title, :description, :price, :activated)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE product SET title = :title, "
                        . "description = :description, price = :price, activated = :activated"
                        . " WHERE id = :id", $params);
    }

    public static function delete(array $id) {
        return parent::modify("DELETE FROM product WHERE id = :id", $id);
    }

    public static function get(array $id) {
        $products = parent::query("SELECT id, title, description, price, activated"
                        . " FROM product"
                        . " WHERE id = :id", $id);

        if (count($products) == 1) {
            return $products[0];
        } else {
            throw new InvalidArgumentException("No such product");
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, title, price, activated, description"
                        . " FROM product"
                        . " ORDER BY id ASC");
    }

    public static function getAllwithURI(array $prefix) {
        return parent::query("SELECT id, title, price, activated, "
                        . "          CONCAT(:prefix, id) as uri "
                        . "FROM product "
                        . "ORDER BY id ASC", $prefix);
    }

}
