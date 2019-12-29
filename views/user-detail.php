<?php if($_SESSION["type"] != 2) { ?>
   <!DOCTYPE html>
   <html>
       <head>
           <meta charset="UTF-8">
           <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
           <meta name="description" content="Online shop">
           <meta name="author" content="Simen Ravnik">

           <title>Shop</title>

           <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

           <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
           <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap4-retro.min.css" ?>">
           <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "shop-homepage.css" ?>">

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
                              <a class="nav-link active" href="<?= BASE_URL . "users" ?>">Customers</a>
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
                  <?php if ($_SESSION["type"] == 1) { ?>
                     <h1 class="my-4">Customers</h1>
                 <?php } else if ($_SESSION["type"] == 0) { ?>
                     <h1 class="my-4">Sellers</h1>
                 <?php } ?>
               </div>
               <!-- /.col-lg-3 -->

               <div class="col-lg-9">

                  <div class="wrapper">
                      <br>
                      <h2><?= $name ?> <?= $lastName ?></h2>
                      <a href="<?= BASE_URL . "users/edit/" . $id ?>" class="btn btn-sm btn-primary my-1 my-sm-0">
                      <span class="fas fa-lock mr-1"></span>
                        Edit
                      </a>
                      <br>
                      <br>

                      <form>
                          <input type="hidden" name="id" value="<?= $id ?>"  />
                          <div class="form-group">
                              <label>Firstname</label>
                              <input type="text" name="name" class="form-control" value="<?= $name ?>" required readonly>
                          </div>

                          <div class="form-group">
                              <label>Lastname</label>
                              <input type="text" name="lastName" class="form-control" value="<?= $lastName ?>" required readonly>
                          </div>

                          <div class="form-group">
                              <label>Email</label>
                              <input type="text" name="email" class="form-control" value="<?= $email ?>" autocomplete="off" required readonly>
                          </div>

                          <div class="form-group">
                             <label>User type</label>
                             <select class="form-control" name="type" disabled>
                                 <?php if($_SESSION["type"] == 0) { ?>
                                     <option value="1" choose selected>Seller</option>
                                 <?php } else if ($_SESSION["type"] == 1) { ?>
                                     <option value="2" choose selected>Customer</option>
                                 <?php } ?>
                             </select>
                          </div>

                          <input type="hidden" name="password" value="<?= $password ?>" />
                          <input type="hidden" name="address" value="<?= $address ?>" />
                          <input type="hidden" name="zipcode_id" value="<?= $zipcode_id ?>" />
                          <input type="hidden" name="phone" value="<?= $phone ?>" />

                          <div class="custom-control custom-checkbox checkbox-lg">
                             <input type="checkbox" name="activated" class="custom-control-input" id="checkbox-2" <?php if(isset($activated) && $activated == 1){print " checked=\"checked\"";} ?> onclick="return false;">
                             <label class="custom-control-label" for="checkbox-2">Activated</label>
                          </div>

                      </form>
                      <br>
                  </div>

               </div>
               <!-- /.col-lg-9 -->

             </div>
             <!-- /.row -->

           </div>
           <!-- /.container -->

           <script src='https://www.google.com/recaptcha/api.js'></script>
           <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
           <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
           <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

       </body>
   </html>
<?php } ?>
