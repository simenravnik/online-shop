
<?php

require_once("app_server/model/DB.php");

$name = $lastName = $email = $password = $address = $zipcode_id = $phone = "";
$name_err = $lastName_err = $email_err = $password_err = $address_err = $zipcode_id_err = $phone_err = "";

/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

// Processing form data when submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } elseif(strlen(trim($_POST["name"])) < 2){
        $name_err = "Name length must be at least 2.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate last name
    if(empty(trim($_POST["lastName"]))){
        $lastName_err = "Please enter your lastname.";
    } elseif(strlen(trim($_POST["lastName"])) < 2){
        $lastName_err = "Lastname length must be at least 2.";
    } else{
        $lastName = trim($_POST["lastName"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM user WHERE email = ?";

        if($stmt = $mysqli->prepare("SELECT id FROM user WHERE email = ?")){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $email_err = "Email already exists. Please enter new one.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Something went wrong, please try again.";
            }
            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } elseif(strlen(trim($_POST["password"])) < 4){
        $password_err = "Password must be at least 4 characters long.";
    } elseif(strlen(trim($_POST["password"])) > 25) {
        $password_err = "Password must be shorter than 25 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter your address.";
    } elseif(strlen(trim($_POST["address"])) < 4){
        $address_err = "Addres must be at least 4 characters long";
    } else{
        $address = trim($_POST["address"]);
    }

    // Validate zipcode_id
    if(empty(trim($_POST["zipcode_id"]))){
        $zipcode_id_err = "Please enter your zip code.";
    } else{
        $zipcode_id = trim($_POST["zipcode_id"]);
    }

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter your phone number.";
    } elseif(strlen(trim($_POST["phone"])) < 9){
        $phone_err = "Phone number form not correct (e. 051831672).";
    } else{
        $phone = trim($_POST["phone"]);
    }

    // Check input errors before inserting in database
    if($_POST["g-recaptcha-response"] != "" && empty($name_err) && empty($lastName_err) && empty($email_err) && empty($password_err) && empty($address_err) && empty($zipcode_id_err) && empty($phone_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO user (name, lastName, email, password, type, address, zipcode_id, phone, activated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssisisi", $param_name, $param_lastName, $param_email,
                                $salted_password, $param_type, $param_address, $param_zipcode_id, $param_phone, $param_activated);

            // Set parameters
            $param_name = $name;
            $param_lastName = $lastName;
            $param_email = $email;

            // Generate a salted password
            $salted_password = password_hash($password, PASSWORD_DEFAULT);

            $param_type = 2;
            $param_address = $address;
            $param_zipcode_id = $zipcode_id;
            $param_phone = $phone;
            $param_activated = 1;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                RegistrationController::login();
            } else{
                echo "Nekaj je šlo narobe, poskusite znova.";
            }
            // Close statement
            $stmt->close();
        }
    }
    // Close connection
    $mysqli->close();
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
                      <a class="nav-link active" href="<?= BASE_URL . "registration" ?>">Register</a>
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
              <h1 class="my-4">Registration</h1>
            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

               <div class="wrapper">
                   <br>
                   <h2>Create new account</h2>
                   <br>
                   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                       <div class="form-group">
                           <label>Firstname</label>
                           <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?= $name ?>">
                           <span class="help-block <?php echo (!empty($name_err)) ? 'text-danger' : ''; ?>"><?php echo $name_err; ?></span>
                       </div>

                       <div class="form-group">
                           <label>Lastname</label>
                           <input type="text" name="lastName" class="form-control <?php echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?= $lastName ?>">
                           <span class="help-block <?php echo (!empty($lastName_err)) ? 'text-danger' : ''; ?>"><?php echo $lastName_err; ?></span>
                       </div>

                       <div class="form-group">
                           <label>Email</label>
                           <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?= $email ?>" autocomplete="off">
                           <span class="help-block <?php echo (!empty($email_err)) ? 'text-danger' : ''; ?>"><?php echo $email_err; ?></span>
                       </div>

                       <div class="form-group">
                           <label>Password</label>
                           <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?= $password ?>"cautocomplete="off">
                           <span class="help-block <?php echo (!empty($password_err)) ? 'text-danger' : ''; ?>"><?php echo $password_err; ?></span>
                       </div>

                       <div class="row">
                         <div class="col-md-8 mb-3">
                            <div class="form-group">
                               <label>Address</label>
                               <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?= $address ?>">
                               <span class="help-block <?php echo (!empty($address_err)) ? 'text-danger' : ''; ?>"><?php echo $address_err; ?></span>
                           </div>
                         </div>
                         <div class="col-md-4 mb-3">
                            <div class="form-group">
                               <label>Zip</label>
                               <select class="form-control <?php echo (!empty($zipcode_id_err)) ? 'is-invalid' : ''; ?>" name="zipcode_id">
                                   <option value="1" selected>1000 Ljubljana</option>
                                   <option value="2">2000 Maribor</option>
                                   <option value="3">3000 Celje</option>
                                   <option value="4">4000 Kranj</option>
                                   <option value="5">5000 Nova Gorica</option>
                               </select>
                               <span class="help-block <?php echo (!empty($zipcode_id_err)) ? 'text-danger' : ''; ?>"><?php echo $zipcode_id_err; ?></span>
                           </div>
                         </div>
                       </div>

                       <div class="form-group">
                           <label>Phone number</label>
                           <input type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?= $phone ?>">
                           <span class="help-block <?php echo (!empty($phone_err)) ? 'text-danger' : ''; ?>"><?php echo $phone_err; ?></span>
                       </div>
                       <div class="form-group">
                          <label>Validation</label>
                          <div class="g-recaptcha" data-sitekey="6LfIOYkUAAAAAJBdVnr6fiQWYguSPm92R7e1Dku-"></div>
                       </div>
                       <hr class="mb-4">
                       <div class="form-group">
                           <input type="submit" class="btn btn-primary btn-md btn-block" value="Submit">
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
        <br>

        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
</html>
