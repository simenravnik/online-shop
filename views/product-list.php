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
    'kolicina' => [
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
            if ($data["kolicina"] > 0) {
                $_SESSION["cart"][$data["id"]] = $data["kolicina"];
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
        <div id="page-container">
           <div id="content-wrap">
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

                    <h1 class="my-4">Products</h1>
                       <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
                          <div class="cart">
                              <h3>Cart</h3>

                              <?php
                              $kosara = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];

                              if ($kosara) {
                                   $znesek = 0;
                                      foreach ($kosara as $id => $kolicina):
                                          $product = ProductDB::get(array("id" => $id));
                                          $znesek += $product["price"] * $kolicina;
                                          ?>
                                          <form action="<?= BASE_URL . "products" ?>" method="post">
                                              <input type="hidden" name="do" value="update_cart" />
                                              <input type="hidden" name="id" value="<?= $product["id"] ?>" />
                                              <div class="list-group">
                                                 <li class="list-group-item">
                                                    <?=
                                                    (strlen($product["title"]) < 50) ?
                                                            $product["title"] :
                                                            substr($product["title"], 0, 26) . " ..."
                                                    ?> (<?= number_format($product["price"], 2) ?> €)
                                                    <div class="form-group row">
                                                       <div class="col-8">
                                                          <input type="number" class="form-control" name="kolicina" value="<?= $kolicina ?>" class="short_input" />
                                                       </div>
                                                       <div class="col-4">
                                                          <button class="update-cart btn btn-primary" type="submit"><i class="fas fa-sync-alt"></i></button>
                                                       </div>
                                                    </div>

                                                 </li>
                                              </div>
                                              <br>
                                          </form>
                                      <?php endforeach; ?>
                                   <p>Together: <b><?= number_format($znesek, 2) ?> EUR</b></p>

                                   <form action="<?= BASE_URL . "orders/confirmation" ?>" method="POST">
                                         <button type="submit" class="btn btn-success gumb"><i class="fas fa-receipt"></i></i> Complete order</button>
                                   </form>
                                   <br>
                                   <form action="<?= BASE_URL . "products" ?>" method="POST">
                                       <input type="hidden" name="do" value="purge_cart" />
                                       <button type="submit" class="btn btn-danger gumb"><i class="fas fa-trash-alt"></i> Empty cart</button>
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

                    <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                      <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                      </ol>
                      <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                          <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="First slide">
                        </div>
                        <div class="carousel-item">
                          <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                          <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Third slide">
                        </div>
                      </div>
                      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    </div>

                    <div class="row">
                      <?php foreach ($products as $product): ?>
                         <div class="col-lg-4 col-md-6 mb-4">
                           <div class="card h-100">
                             <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
                             <div class="card-body">
                               <h4 class="card-title">
                                 <a href="<?= BASE_URL . "products/" . $product["id"] ?>"><?= $product["title"] ?></a>
                               </h4>
                               <h5><?= number_format($product["price"], 2) ?> €</h5>
                               <p class="card-text"><?= $product["description"] ?></p>
                               <form action="<?= BASE_URL . "products" ?>" method="post">
                                  <input type="hidden" name="do" value="add_into_cart" />
                                  <input type="hidden" name="id" value="<?= $product["id"] ?>" />
                                  <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
                                      <?php if($product["activated"] == 1) { ?>
                                          <button type="submit" class="btn btn-outline-warning add-to-cart"><i class="fas fa-cart-plus"></i> Add to cart</button>
                                      <?php } else { ?>
                                          <p>Not available at the moment</p>
                                      <?php } ?>
                                  <?php } ?>
                              </form>
                             </div>
                             <div class="card-footer">
                               <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                             </div>
                           </div>
                         </div>
                         <?php endforeach; ?>
                    </div>
                    <!-- /.row -->

                  </div>
                  <!-- /.col-lg-9 -->

                </div>
                <!-- /.row -->

              </div>
              <!-- /.container -->

           </div>
           <!-- Footer -->
           <footer id="footer" class="py-5" style="background-color: #F5F5F5;">
              <div class="container">
                <p class="m-0 text-center">Copyright &copy; online-shop 2020</p>
              </div>
              <!-- /.container -->
           </footer>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
</html>
