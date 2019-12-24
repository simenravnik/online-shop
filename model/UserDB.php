<?php

//require_once 'model/AbstractDB.php';

class UserDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO user (name, lastName, email, password, type, address, zipcode_id, phone, activated) "
                        . " VALUES (:name, :lastName, :email, :password, :type, :address, :zipcode_id, :phone, :activated)", $params);
    }

    public static function update(array $params) {
        return parent::modify("UPDATE user SET name = :name, "
                        . "lastName = :lastName, email = :email, password = :password, "
                        . "type = :type, address = :address, zipcode_id = :zipcode_id, phone = :phone, activated = :activated"
                        . " WHERE id = :id", $params);
    }

    public static function delete(array $id) {
        return parent::modify("DELETE FROM user WHERE id = :id", $id);
    }

    public static function get(array $id) {
        $users = parent::query("SELECT id, name, lastName, email, password, type, address, zipcode_id, phone, activated"
                        . " FROM user"
                        . " WHERE id = :id", $id);

        if (count($users) == 1) {
            return $users[0];
        } else {
            throw new InvalidArgumentException("No such user");
        }
    }

    public static function getAll() {
        return parent::query("SELECT id, name, lastName, email, password, type, address, zipcode_id, phone, activated"
                        . " FROM user"
                        . " ORDER BY id ASC");
    }

    public static function getAllwithURI(array $prefix) {
        return parent::query("SELECT id, name, lastName, email, activated "
                        . "          CONCAT(:prefix, id) as uri "
                        . "FROM user "
                        . "ORDER BY id ASC", $prefix);
    }
}
