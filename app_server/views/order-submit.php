<?php
   if(isset($_SESSION["cart"])) $cart = $_SESSION["cart"];

   $order_id = OrdersController::add(["id_user" => $_SESSION["id"], "id_seller" => 2, "status" => 1]);
   foreach($cart as $id => $amount):
       OrderProductsController::add(["id_order" => $order_id, "id_product" => $id, "amount" => $amount]);
   endforeach;

   // Unset the cart
   $_SESSION["cart"] = array();

   // Redirect to login page
   header("location:". BASE_URL . "orders");
   exit;
?>
