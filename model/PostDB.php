
<?php
    //require_once 'model/AbstractDB.php';

    class PostDB extends AbstractDB {

        public static function insert(array $params) {
            return parent::modify("INSERT INTO post_office (zipcode) "
                            . " VALUES (:zipcode)", $params);
        }

        public static function update(array $params) {
            return parent::modify("UPDATE post_office SET zipcode = :zipcode, "
                            . " WHERE id = :id", $params);
        }

        public static function delete(array $id) {
            return parent::modify("DELETE FROM post_office WHERE id = :id", $id);
        }

        public static function get(array $id) {
            $users = parent::query("SELECT id, zipcode"
                            . " FROM post_office"
                            . " WHERE id = :id", $id);

            if (count($users) == 1) {
                return $users[0];
            } else {
                throw new InvalidArgumentException("No such post office");
            }
        }

        public static function getAll() {
            return parent::query("SELECT id, zipcode"
                            . " FROM post_office"
                            . " ORDER BY id ASC");
        }

        public static function getAllwithURI(array $prefix) {
            return parent::query("SELECT id, zipcode"
                            . "          CONCAT(:prefix, id) as uri "
                            . "FROM post_office "
                            . "ORDER BY id ASC", $prefix);
        }
    }

?>
