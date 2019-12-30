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

               <div class="col-lg-9">
                  <div class="wrapper">

                     <!-- PENDING ORDERS -->
                      <br>
                        <h2>Pending orders</h2>
                      <br>
                       <?php if(isset($_SESSION["loggedin"])) { ?>
                           <table class="table">
                              <thead class="thead-light">
                                 <tr>
                                   <th scope="col">#</th>
                                   <th scope="col">Order</th>
                                   <th scope="col">Status</th>
                                 </tr>
                              </thead>
                           <tbody>
                           <?php if ($_SESSION["type"] == 2) {
                               $i = 1;
                               foreach ($orders as $order):
                                   if ($order["id_user"] == $_SESSION["id"]) {
                                      if ($order["status"] == 1) {
                                         $order["number"] = $i;
                                         $i++;
                                         ?>
                                          <tr>
                                            <th scope="row"><?= $order["number"] ?></th>
                                            <td>
                                              <a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                                                 ORDER No. <?= $order["id"] ?>
                                              </a>
                                            </td>
                                            <td>
                                              <span class="badge badge-pill badge-primary"><span class="fas fa-clock mr-2"></span>PENDING . . . </span>
                                            </td>
                                          </tr>
                                       <?php } ?>
                               <?php } endforeach; ?>
                           <?php } else if ($_SESSION["type"] == 1) {
                               $i = 1;
                               foreach ($orders as $order):
                                      if ($order["status"] == 1) {
                                         $order["number"] = $i;
                                         $i++;
                                         ?>
                                          <tr>
                                            <th scope="row"><?= $order["number"] ?></th>
                                            <td>
                                              <a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                                                 ORDER No. <?= $order["id"] ?>
                                              </a>
                                            </td>
                                            <td>
                                               <div class="row">
                                                   <form action="<?= BASE_URL . "orders/edit/" . $order["id"] ?>" method="post">
                                                         <input type="hidden" name="id" value="<?= $order["id"] ?>"  />
                                                         <input type="hidden" name="id_user" value="<?= $order["id_user"] ?>" />
                                                         <input type="hidden" name="id_seller" value="<?= $_SESSION["id"]  ?>" />
                                                         <input type="hidden" name="status" value="0" />
                                                         <button name="confirm" type="submit" class="btn btn-sm btn-primary my-1 my-sm-0 mr-1">
                                                             Confirm</button>
                                                    </form>
                                                    <form action="<?= BASE_URL . "orders/edit/" . $order["id"] ?>" method="post">
                                                          <input type="hidden" name="id" value="<?= $order["id"] ?>"  />
                                                          <input type="hidden" name="id_user" value="<?= $order["id_user"] ?>" />
                                                          <input type="hidden" name="id_seller" value="<?= $_SESSION["id"]  ?>" />
                                                          <input type="hidden" name="status" value="2" />
                                                          <button type="submit" class="btn btn-sm btn-danger my-1 my-sm-0">
                                                             Decline</button>
                                                   </form>
                                            </div>
                                            </td>
                                          </tr>
                               <?php } endforeach; ?>
                           <?php } ?>
                           </tbody>
                           </table>
                       <?php } ?>

                      <br>

                     <!-- CONFIRMED ORDERS -->
                      <br>
                        <h4>Confirmed orders</h4>
                      <br>
                       <?php if(isset($_SESSION["loggedin"])) { ?>
                           <table class="table">
                              <thead class="thead-light">
                                 <tr>
                                   <th scope="col">#</th>
                                   <th scope="col">Order</th>
                                   <th scope="col">Status</th>
                                 </tr>
                              </thead>
                           <tbody>
                           <?php if ($_SESSION["type"] == 2) {
                               $i = 1;
                               foreach ($orders as $order):
                                   if ($order["id_user"] == $_SESSION["id"]) {
                                      if ($order["status"] == 0) {
                                         $order["number"] = $i;
                                         $i++;
                                         ?>
                                          <tr>
                                            <th scope="row"><?= $order["number"] ?></th>
                                            <td>
                                              <a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                                                 ORDER No. <?= $order["id"] ?>
                                              </a>
                                            </td>
                                            <td>
                                              <span class="badge badge-pill badge-success"><span class="fas fa-check mr-1"></span>CONFIRMED</span>
                                            </td>
                                          </tr>
                                       <?php } ?>
                               <?php } endforeach; ?>
                           <?php } else if ($_SESSION["type"] == 1) {
                               $i = 1;
                               foreach ($orders as $order):
                                      if ($order["status"] == 0) {
                                         $order["number"] = $i;
                                         $i++;
                                         ?>
                                          <tr>
                                            <th scope="row"><?= $order["number"] ?></th>
                                            <td>
                                              <a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                                                 ORDER No. <?= $order["id"] ?>
                                              </a>
                                            </td>
                                            <td>
                                              <span class="badge badge-pill badge-success"><span class="fas fa-check mr-1"></span>CONFIRMED</span>
                                            </td>
                                          </tr>
                               <?php } endforeach; ?>
                           <?php } ?>
                           </tbody>
                           </table>
                       <?php } ?>

                       <!-- DECLINED ORDERS -->
                        <br>
                          <h4>Declined orders</h4>
                        <br>
                         <?php if(isset($_SESSION["loggedin"])) { ?>
                             <table class="table">
                                <thead class="thead-light">
                                   <tr>
                                     <th scope="col">#</th>
                                     <th scope="col">Order</th>
                                     <th scope="col">Status</th>
                                   </tr>
                                </thead>
                             <tbody>
                             <?php if ($_SESSION["type"] == 2) {
                                 $i = 1;
                                 foreach ($orders as $order):
                                     if ($order["id_user"] == $_SESSION["id"]) {
                                        if ($order["status"] == 2) {
                                           $order["number"] = $i;
                                           $i++;
                                           ?>
                                            <tr>
                                              <th scope="row"><?= $order["number"] ?></th>
                                              <td>
                                                <a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                                                   ORDER No. <?= $order["id"] ?>
                                                </a>
                                              </td>
                                              <td>
                                                <span class="badge badge-pill badge-danger"><span class="fas fa-ban mr-3"></span>DECLINED</span>
                                              </td>
                                            </tr>
                                         <?php } ?>
                                 <?php } endforeach; ?>
                             <?php } else if ($_SESSION["type"] == 1) {
                                 $i = 1;
                                 foreach ($orders as $order):
                                        if ($order["status"] == 2) {
                                           $order["number"] = $i;
                                           $i++;
                                           ?>
                                            <tr>
                                              <th scope="row"><?= $order["number"] ?></th>
                                              <td>
                                                <a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                                                   ORDER No. <?= $order["id"] ?>
                                                </a>
                                              </td>
                                              <td>
                                                <span class="badge badge-pill badge-danger"><span class="fas fa-ban mr-3"></span>DECLINED</span>
                                              </td>
                                            </tr>
                                 <?php } endforeach; ?>
                             <?php } ?>
                             </tbody>
                             </table>
                         <?php } ?>

                  </div>
               </div>
               <!-- /.col-lg-9 -->

             </div>
             <!-- /.row -->

           </div>
           <!-- /.container -->
           <br>

           <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
           <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
           <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

       </body>
   </html>
<?php } ?>
