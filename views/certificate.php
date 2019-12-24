<?php

// Include config file
require_once("model/DB.php");

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $activated_err = "";
/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Prosimo vnesite email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Prosimo vnesite geslo.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        $client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");
        if ($client_cert == null) {
            die('err: Spremenljivka SSL_CLIENT_CERT ni nastavljena.');
        }
        $cert_data = openssl_x509_parse($client_cert);
        $emailCert = (is_array($cert_data['subject']['emailAddress']) ?
                        $cert_data['subject']['emailAddress'][0] : $cert_data['subject']['emailAddress']);

        // Prepare a select statement
        $sql = "SELECT id, name, lastName, email, password, type, address, zipcode_id, phone, activated FROM user WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            //mysqli_stmt_bind_param($stmt, "s", $param_email);
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $email;

            $uporabnikEmail = $email;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $name, $lastName, $email, $hashed_password, $type, $address, $zipcode_id, $phone, $activated);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)) {
                            if ($type != 2 && $emailCert == $email) {
                                if($activated == 1) {
                                    //session_regenerate_id();
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["name"] = $name;
                                    $_SESSION["lastName"] = $lastName;
                                    $_SESSION["email"] = $email;
                                    $_SESSION["type"] = $type;
                                    $_SESSION["address"] = $address;
                                    $_SESSION["zipcode_id"] = $zipcode_id;
                                    $_SESSION["phone"] = $phone;
                                    $_SESSION["activated"] = $activated;
                                    LoginController::logged_in();
                                } else {
                                    $password_err = "Prijava neaktiviranim uporabnikom ni mogoča.";
                                }
                            } else if ($type == 2) {
                                if($activated == 1) {
                                    //session_regenerate_id();
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["name"] = $name;
                                    $_SESSION["lastName"] = $lastName;
                                    $_SESSION["email"] = $email;
                                    $_SESSION["type"] = $type;
                                    $_SESSION["address"] = $address;
                                    $_SESSION["zipcode_id"] = $zipcode_id;
                                    $_SESSION["phone"] = $phone;
                                    $_SESSION["activated"] = $activated;
                                    LoginController::logged_in();
                                } else {
                                    // Display an error message if password is not valid
                                    //$password_err = "Napačno geslo!";
                                    echo "$uporabnikEmail ni avtoriziran uporabnik!";
                                }
                            } else {
                                echo "$uporabnikEmail ni avtoriziran uporabnik!";
                            }
                        } else {
                            $password_err = "Napačno geslo.";
                        }
                    } else{
                        // Display an error message if email doesn't exist
                        $email_err = "Račun s tem email naslovom ne obstaja.";
                    }
                } else{
                    $email_err = "Račun s tem email naslovom ne obstaja.";
                }
                // Close statement
                $stmt->close();
            }
        }
        // Close connection
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Prijava</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?= $email ?>" autocomplete="off">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Geslo</label>
                <input type="password" name="password" class="form-control" autocomplete="off">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Prijava">
            </div>
        </form>
    </div>
</body>
</html>
