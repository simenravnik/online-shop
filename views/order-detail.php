
<?php if($_SESSION["type"] != 0 ) { ?>
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
                   <li class="nav-item">
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
                              <a class="nav-link active" href="<?= BASE_URL . "orders" ?>">Orders</a>
                           </li>
                       <?php } else if ($_SESSION["type"] == 0) { ?>
                           <li class="nav-item">
                              <a class="nav-link" href="<?= BASE_URL . "users" ?>">Sellers</a>
                           </li>
                        <?php } else if ($_SESSION["type"] == 2 ){ ?>
                           <li class="nav-item">
                              <a class="nav-link active" href="<?= BASE_URL . "orders" ?>">My orders</a>
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

                  <?php if ($_SESSION["type"] != 0) { ?>
                     <a href="<?= BASE_URL . "orders/" ?>" style="color: black;">
                        <h1 class="my-4">Orders</h1>
                     </a>
                 <?php } ?>


               </div>
               <!-- /.col-lg-3 -->

               <?php
                   $customer = UsersController::getUserDetails($id_user);
                   if ($id_seller != 0) {
                       $seller = UsersController::getUserDetails($id_seller);
                   }
                   $post = PostController::get($customer["zipcode_id"]);
                   $order_products = OrderProductsController::getOrderProductsById($id);
               ?>

               <div class="col-lg-9">
                  <div class="wrapper">
                     <br>
                       <h2>Order details</h2>
                     <br>

                       <div class="card">
                     <div class="card-header">
                     Order No.
                     <strong><?= $id ?></strong>
                       <span class="float-right"> <strong>Status:</strong>
                          <?php if ($status == 0) { ?> Confirmed
                          <?php } else if ($status == 1) { ?> Pending
                          <?php } else if ($status == 2) { ?> Declined
                                      <?php } ?>
                       </span>

                     </div>
                     <div class="card-body">
                     <div class="row mb-4">

                     <div class="col-sm-6">
                     <h6 class="mb-3">Customer:</h6>
                     <div>
                     <strong><?= $customer["name"] ?> <?= $customer["lastName"] ?></strong>
                     </div>
                     <div><?= $customer["address"] ?></div>
                     <div><?= $post["zipcode"] ?></div>
                     <div>Email: <?= $customer["email"]?></div>
                     <div>Phone: <b><?= $customer["phone"]?></b></div>
                     </div>

                     <div class="col-sm-6">
                     <h6 class="mb-3">Seller:</h6>
                     <div>
                     <strong><?= $seller["name"] ?> <?= $seller["lastName"] ?></strong>
                     <div>Email: <?= $seller["email"]?></div>
                     </div>
                     </div>


                     </div>

                     <div class="table-responsive-sm">
                     <table class="table table-striped">
                     <thead>
                     <tr>
                     <th class="center">#</th>
                     <th>Item</th>
                     <th>Description</th>

                     <th class="right">Unit Cost</th>
                       <th class="center">Qty</th>
                     <th class="right">Total</th>
                     </tr>
                     </thead>
                     <tbody>
                        <?php
                        $total = 0;
                        $i = 0;
                        foreach ($order_products as $order_product):
                            $product = ProductsController::getProductDetails($order_product["id_product"]);
                            $total += $order_product["amount"] * $product["price"];
                            $i ++;
                             ?>
                              <tr>
                                 <td class="center"><?= $i ?></td>
                                 <td class="left strong"><?= $product["title"] ?></td>
                                 <?php if (strlen($product["description"]) < 18) { ?>
                                    <td class="left"><?= $product["description"] ?></td>
                                 <?php } else {
                                    $description_cut = substr($product["description"], 0, 18);
                                    $description_cut .= "...";
                                    ?>
                                    <td class="left"><?= $description_cut ?></td>
                                 <?php } ?>

                                 <td class="right"><?= $product["price"] ?> €</td>
                                   <td class="center"><?= $order_product["amount"] ?></td>
                                 <td class="right"><?= $order_product["amount"] * $product["price"]; ?> €</td>
                              </tr>
                        <?php endforeach;    ?>
                     </tbody>
                     </table>
                     </div>
                     <div class="row">
                     <div class="col-lg-4 col-sm-5">

                     </div>

                     <div class="col-lg-4 col-sm-5 ml-auto">
                     <table class="table table-clear">
                     <tbody>
                        <tr>
                           <td class="left">
                              <strong>Total</strong>
                           </td>
                           <td class="right">
                              <strong><?= number_format($total, 2) ?> €</strong>
                           </td>
                        </tr>
                     </tbody>
                     </table>

                     </div>

                     </div>

                     </div>
                     </div>


                     <?php if(isset($_SESSION["loggedin"])) {
                              if ($_SESSION["type"] == 1) { ?>
                              <?php if ($status != 2) { ?>
                                 <br>
                                    <div class="row float-right">
                                       <?php if ($status == 1) { ?>
                                       <div class="col pr-1" >
                                        <form action="<?= BASE_URL . "orders/edit/" . $id ?>" method="post">
                                              <input type="hidden" name="id" value="<?= $id ?>"  />
                                              <input type="hidden" name="id_user" value="<?= $id_user ?>" />
                                              <input type="hidden" name="id_seller" value="<?= $_SESSION["id"]  ?>" />
                                              <input type="hidden" name="status" value="0" />
                                              <button name="confirm" type="submit" class="btn btn-md btn-primary my-1 my-sm-0">
                                                  Confirm</button>
                                         </form>
                                         </div>
                                         <?php } ?>
                                         <div class="col pl-1" >
                                         <form action="<?= BASE_URL . "orders/edit/" . $id ?>" method="post">
                                               <input type="hidden" name="id" value="<?= $id ?>"  />
                                               <input type="hidden" name="id_user" value="<?= $id_user ?>" />
                                               <input type="hidden" name="id_seller" value="<?= $_SESSION["id"]  ?>" />
                                               <input type="hidden" name="status" value="2" />
                                               <button type="submit" class="btn btn-md btn-danger my-1 my-sm-0">
                                                  Decline</button>
                                        </form>
                                        </div>
                                 </div>
                              <?php }
                              }
                           } ?>

                  </div>
                  <br>
               </div>
               <!-- /.col-lg-9 -->

             </div>
             <!-- /.row -->

           </div>
           <!-- /.container -->

           <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
           <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
           <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

       </body>
   </html>
<?php } ?>
