<?php

require_once('app_server/model/AbstractDB.php');

class ProductRateDB extends AbstractDB {

   public static function insert(array $params) {
      return parent::modify("INSERT INTO rate (id_product, num_ratings, rating) "
                       . " VALUES (:id_product, :num_ratings, :rating)", $params);
   }

   public static function update(array $params) {
      return parent::modify("UPDATE rate SET "
                       . "num_ratings = :num_ratings, rating = :rating"
                       . " WHERE id_product = :id_product", $params);
   }

   public static function delete(array $id_product) {
      return parent::modify("DELETE FROM rate WHERE id_product = :id_product", $id_product);
   }

   public static function get(array $id_product) {
      $all_ratings = parent::query("SELECT id_product, num_ratings, rating"
                       . " FROM rate"
                       . " WHERE id_product = :id_product", $id_product);

      return $all_ratings;
   }

   public static function getAll() {
      return parent::query("SELECT id_product, num_ratings, rating"
                       . " FROM rate"
                       . " ORDER BY id_product ASC");
   }

   public static function getAllwithURI(array $prefix) {
      return parent::query("SELECT id_product, num_ratings, rating"
                       . "          CONCAT(:prefix, id_product) as uri "
                       . "FROM rate "
                       . "ORDER BY id_product ASC", $prefix);
   }

}
