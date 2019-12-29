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
            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

               <div class="wrapper">
                   <br>
                   <h2><?= $title ?></h2>
                   <?php if(isset($_SESSION["loggedin"])) { ?>
                      <?php if($_SESSION["type"] == 1) { ?>
                          <form action="<?= BASE_URL . "products/delete/" . $id ?>" method="post">
                             <input type="hidden" name="id" value="<?= $id ?>" />
                             <input type="hidden" name="title" value="<?= $title ?>"/>
                             <input type="hidden" name="price" step="0.01" min="0" value="<?= $price ?>"/>
                             <input type="hidden" name="activated" value="<?= $activated ?>"  />
                             <input type="hidden" name="description" value="<?= $description ?>"  />
                             <td>
                                <a href="<?= BASE_URL . "products/edit/" . $id ?>" class="btn btn-sm btn-primary my-1 my-sm-0">
                                  <span class="fas fa-edit mr-1"></span>
                                  Edit</a>

                                <button class="btn btn-sm btn-danger my-1 my-sm-0">
                                 <span class="fas fa-trash mr-1"></span>
                                 Delete</button>
                             </td>
                         </form>
                      <?php } ?>
                   <?php } ?>

                   <div class="card mt-4">
                     <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
                     <div class="card-body">
                       <h3 class="card-title"><?= $title ?></h3>
                       <h4><?= $price ?> €</h4>
                       <p class="card-text"><?= $description ?></p>
                       <p class="card-text <?= $activated ? "text-success" : "text-danger" ?>"><?= $activated ? "Available." : "Not available at the moment." ?></p>
                       <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                       4.0 stars
                     </div>
                   </div>

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
