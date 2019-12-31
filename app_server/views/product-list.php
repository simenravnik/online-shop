<?php

$url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
$validationRules = ['do' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
            "regexp" => "/^(add_into_cart|update_cart|purge_cart)$/"
        ]
    ],
    'id' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0]
    ],
    'amount' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0]
    ]
];

$data = filter_input_array(INPUT_POST, $validationRules);


switch ($data["do"]) {
    case "add_into_cart":
        try {
            $product = ProductDB::get(array("id" => $data["id"]));

            if (isset($_SESSION["cart"][$product["id"]])) {
                $_SESSION["cart"][$product["id"]] ++;
            } else {
                $_SESSION["cart"][$product["id"]] = 1;
            }
        } catch (Exception $exc) {
            die($exc->getMessage());
        }
        break;
    case "update_cart":
        if (isset($_SESSION["cart"][$data["id"]])) {
            if ($data["amount"] > 0) {
                $_SESSION["cart"][$data["id"]] = $data["amount"];
            } else {
                unset($_SESSION["cart"][$data["id"]]);
            }
        }
        break;
    case "purge_cart":
        unset($_SESSION["cart"]);
        break;
    default:
        break;
}

$search = "";
   // Processing form data when submitted
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      if (!empty($_POST["search"])) {
         $search = $_POST["search"];
      }
   }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Online shop">
        <meta name="author" content="Simen Ravnik">

        <title>Shop</title>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

        <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "shop-homepage.css" ?>">
        <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap4-retro.min.css" ?>">

        <link href="https://fonts.googleapis.com/css?family=Montserrat|Shrikhand" rel="stylesheet">

    <head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-sm navbar-light fixed-top" style="background-color: #ffffff;">
          <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL . "products" ?>">Online Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
             <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="<?= BASE_URL . "products" ?>">Home
                    <span class="sr-only">(current)</span>
                  </a>
                </li>
                <?php if(!isset($_SESSION["loggedin"])) { ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= BASE_URL . "registration" ?>">Register</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= BASE_URL . "login" ?>">Login</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= BASE_URL . "certificate" ?>">Admins/Sellers</a>
                    </li>
                <?php } else {
                    if($_SESSION["type"] == 1) { ?>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= BASE_URL . "products/add" ?>">Add products</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= BASE_URL . "users" ?>">Customers</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= BASE_URL . "orders" ?>">Orders</a>
                        </li>
                    <?php } else if ($_SESSION["type"] == 0) { ?>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= BASE_URL . "users" ?>">Sellers</a>
                        </li>
                     <?php } else if ($_SESSION["type"] == 2 ){ ?>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= BASE_URL . "orders" ?>">My orders</a>
                        </li>
                     <?php } ?>
                        <li class="nav-item">
                           <a class="nav-link" href="<?= BASE_URL . "profile/" . $_SESSION["id"] ?>">Edit profile</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link btn btn-logout" href="<?= BASE_URL . "logout" ?>">Logout</a>
                        </li>
                <?php } ?>
             </ul>
            </div>
          </div>
        </nav>

        <!-- Page Content -->
        <div class="container">

          <div class="row">

            <div class="col-lg-3">

              <a href="<?= BASE_URL . "products/" ?>" style="color: black;">
                 <h1 class="my-4">Products</h1>
              </a>
              <form class="mb-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                  </div>
                </div>
              </form>
              <br>
                 <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
                    <div class="cart">
                        <h3>Cart</h3>

                        <?php
                        $cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];

                        if ($cart) {
                             $znesek = 0;
                                foreach ($cart as $id => $amount):
                                    $product = ProductDB::get(array("id" => $id));
                                    $znesek += $product["price"] * $amount;
                                    ?>
                                    <form action="<?= BASE_URL . "products" ?>" method="post">
                                        <input type="hidden" name="do" value="update_cart" />
                                        <input type="hidden" name="id" value="<?= $product["id"] ?>" />
                                        <div class="list-group mb-1">
                                           <li class="list-group-item pb-0">
                                              <strong><?=
                                              (strlen($product["title"]) < 15) ?
                                                      $product["title"] :
                                                      substr($product["title"], 0, 15) . " ..."
                                              ?></strong> <span class="text-muted float-right"><?= number_format($product["price"], 2) ?> €</span>
                                              <div class="form-group row mt-2 pb-0">
                                                 <div class="col-6 pr-0">
                                                    <input type="number" class="form-control short_input" name="amount" value="<?= $amount ?>" />
                                                 </div>
                                                 <div class="col-2 pl-1">
                                                    <button class="update-cart btn btn-primary" type="submit"><i class="fas fa-sync-alt"></i></button>
                                                 </div>
                                              </div>

                                           </li>
                                        </div>
                                    </form>
                                <?php endforeach; ?>
                               <br>
                             <table class="table table-clear">
                            <tbody>
                               <tr>
                                  <td class="left">
                                     <strong>Total</strong>
                                  </td>
                                  <td class="right">
                                     <strong><?= number_format($znesek, 2) ?> €</strong>
                                  </td>
                               </tr>
                            </tbody>
                            </table>

                             <form action="<?= BASE_URL . "products" ?>" method="POST">
                                <input type="hidden" name="do" value="purge_cart" />
                                <td>
                                  <a href="<?= BASE_URL . "orders/confirmation" ?>" class="btn btn-sm btn-primary my-1 my-sm-0">
                                     <span class="fas fa-check mr-1"></span>
                                     Complete</a>

                                  <button type="submit" class="btn btn-sm btn-danger my-1 my-sm-0">
                                    <span class="fas fa-trash mr-1"></span>
                                    Empty cart</button>
                                </td>
                             </form>

                        <?php } elseif(!isset($_SESSION["loggedin"])) { ?>
                             Login for adding to cart.
                        <?php } else { ?>
                             Cart is empty.
                        <?php } ?>
                    </div>
                 <?php } ?>
            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

               <br>
               <h2>All products</h2>
               <br>

              <div class="row">
                <?php foreach ($products as $product):
                   $sim = similar_text($product["title"], $search, $perc);
                   if (empty($search) || $perc > 50) { ?>
                      <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                          <a href="<?= BASE_URL . "products/" . $product["id"] ?>">
                             <img class="item-img card-img-top" src="https://hips.hearstapps.com/delish/assets/18/08/1519321899-hard-boiled-eggs-horizontal.jpg" alt="">
                          </a>
                          <div class="card-body">
                            <h4 class="card-title">
                              <a href="<?= BASE_URL . "products/" . $product["id"] ?>">
                                 <?=
                                 (strlen($product["title"]) < 15) ?
                                         $product["title"] :
                                         substr($product["title"], 0, 15) . " ..."
                                 ?>
                              </a>
                            </h4>
                            <h5><?= number_format($product["price"], 2) ?> €</h5>
                            <p class="card-text">
                               <?=
                               (strlen($product["description"]) < 45) ?
                                       $product["description"] :
                                       substr($product["description"], 0, 45) . " ..."
                               ?>
                            </p>
                            <form action="<?= BASE_URL . "products" ?>" method="post">
                               <input type="hidden" name="do" value="add_into_cart" />
                               <input type="hidden" name="id" value="<?= $product["id"] ?>" />
                               <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
                                   <?php if($product["activated"] == 1) { ?>
                                       <button type="submit" class="btn btn-sm btn-outline-warning add-to-cart"><i class="fas fa-cart-plus"></i> Add to cart</button>
                                   <?php } else { ?>
                                       <p>Not available at the moment.</p>
                                   <?php } ?>
                               <?php } ?>
                           </form>
                          </div>
                          <div class="card-footer">
                            <?php  $product_rate_data = ProductRateController::getProductRateById($product["id"]); ?>
                            <small class="text-muted">
                               <?php if ($product_rate_data['0']['rating'] != 0)  { ?>
                               <?php
                                	$starNumber = (isset($product_rate_data['0']['rating'])) ? $product_rate_data['0']['rating'] : 0;
                                	for( $x = 0; $x < 5; $x++ )
                                    {
                                        if( floor($starNumber)-$x >= 1 )
                                        { echo '<i class="fa fa-star"></i>'; }
                                        elseif( $starNumber-$x > 0 )
                                        { echo '<i class="fa fa-star-half-alt"></i>'; }
                                        else
                                        { echo '<i class="fa fa-star-o"></i>'; }
                                    }
                                ?>
                             <?php } else { ?>
                                No ratings yet.
                             <?php } ?>
                            </small>
                          </div>
                        </div>
                      </div>
                   <?php } endforeach; ?>
              </div>
              <!-- /.row -->

            </div>
            <!-- /.col-lg-9 -->

          </div>
          <!-- /.row -->

        </div>
        <!-- /.container -->
        <br>

        <script src="https://kit.fontawesome.com/42e36ef1b7.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
</html>
