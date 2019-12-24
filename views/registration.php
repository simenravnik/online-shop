<?php

require_once("model/DB.php");

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
        $name_err = "Prosimo vnesite svoje ime.";
    } elseif(strlen(trim($_POST["name"])) < 2){
        $name_err = "Ime mora biti dolžine vsaj 2.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate last name
    if(empty(trim($_POST["lastName"]))){
        $lastName_err = "Prosimo vnesite svoj priimek.";
    } elseif(strlen(trim($_POST["lastName"])) < 2){
        $lastName_err = "Priimek mora biti dolžine vsaj 2.";
    } else{
        $lastName = trim($_POST["lastName"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Prosimo vnesite svoj email.";
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
                    $email_err = "Ta email je že zaseden.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Nekaj je šlo narobe, prosimo poskusite še enkrat.";
            }
            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Prosimo vnesite geslo.";
    } elseif(strlen(trim($_POST["password"])) < 4){
        $password_err = "Geslo mora vsebovati vsaj 4 znake.";
    } elseif(strlen(trim($_POST["password"])) > 25) {
        $password_err = "Geslo mora biti krajše od 25 znakov.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Prosimo vnesite svoj naslov.";
    } elseif(strlen(trim($_POST["address"])) < 4){
        $address_err = "Naslov mora biti dolžine vsaj 4.";
    } else{
        $address = trim($_POST["address"]);
    }

    // Validate zipcode_id
    if(empty(trim($_POST["zipcode_id"]))){
        $zipcode_id_err = "Prosimo izberite poštno številko.";
    } else{
        $zipcode_id = trim($_POST["zipcode_id"]);
    }

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Prosimo vnesite svojo telefonsko številko.";
    } elseif(strlen(trim($_POST["phone"])) < 9){
        $phone_err = "Telefonska številka ni pravilne oblike, npr. 030123987.";
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
                CtrlRegistration::login();
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <div class="wrapper">
        <h2>Registracija stranke</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Ime</label>
                <input type="text" name="name" class="form-control" value="<?= $name ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($lastName_err)) ? 'has-error' : ''; ?>">
                <label>Priimek</label>
                <input type="text" name="lastName" class="form-control" value="<?= $lastName ?>">
                <span class="help-block"><?php echo $lastName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?= $email ?>" autocomplete="off">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Geslo</label>
                <input type="password" name="password" class="form-control" value="<?= $password ?>"cautocomplete="off">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                <label>Naslov</label>
                <input type="text" name="address" class="form-control" value="<?= $address ?>">
                <span class="help-block"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($zipcode_id_err)) ? 'has-error' : ''; ?>">
                <select name="zipcode_id">
                    <option value="" disabled selected>Poštna številka</option>
                    <option value="1">1000 Ljubljana</option>
                    <option value="2">2000 Maribor</option>
                    <option value="3">3000 Celje</option>
                    <option value="4">4000 Kranj</option>
                    <option value="5">5000 Nova Gorica</option>
                </select>
                <span class="help-block"><?php echo $zipcode_id_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <label>Telefonska številka</label>
                <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfIOYkUAAAAAJBdVnr6fiQWYguSPm92R7e1Dku-"></div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registriraj se">
            </div>
        </form>
    </div>
</body>
</html>
