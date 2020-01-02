<?php
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header("X-XSS-Protection: 1; mode=block");

require_once("app_server/model/ProductDB.php");
require_once("app_server/controllers/ProductsController.php");
require_once("ViewHelper.php");

class OrdersREST {
    public static function getUserOrders($id) {

        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $resultArray = array();

        // Check connection
        if($mysqli === false){
            die("ERROR: Could not connect. " . $mysqli->connect_error);
        }
        
        // Prepare a select statement
        $sql = "SELECT id, id_user, id_seller, status FROM shop_order WHERE id_user = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_id_user);

            // Set parameters
            $param_id_user = $id;
            
            // Attempt to execute the prepared statement
            $stmt->execute();
                
            // get results
            $result = $stmt->get_result();   // You get a result object now
                
            if($result->num_rows > 0) {     // Note: change to $result->...!
                $number = 1;
                while($data = $result->fetch_assoc()) {
                    $name = "order #" . $number;
                    $number = $number + 1;
                    array_push($resultArray,$data);
                }
            }
                
            // Close statement
            $stmt->close(); 
        }
        
        // Close connection
        $mysqli->close();
        echo ViewHelper::renderJSON($resultArray, 200);
    }
}
