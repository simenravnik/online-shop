<?php
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header("X-XSS-Protection: 1; mode=block");

require_once("app_server/model/ProductDB.php");
require_once("app_server/controllers/ProductsController.php");
require_once("ViewHelper.php");

class AndroidREST {

    public static function login() {

        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Check connection
        if($mysqli === false){
            die("ERROR: Could not connect. " . $mysqli->connect_error);
        }

        $email_err = $password_err = $activated_err = "";
        
        $message = "default";
        $code = 0;
        
        # parsing POST body into descriptor
        #$entityBody = file_get_contents('php://input');
        #$body = json_decode($entityBody, TRUE);
        $rules = [
                'email' => FILTER_SANITIZE_SPECIAL_CHARS,
                'password' => FILTER_SANITIZE_SPECIAL_CHARS
            ];
        $body = filter_input_array(INPUT_POST, $rules);

        $email = $body["email"];
        $password = $body["password"];

        // Check if email is empty
        if(empty($email)){
            $email_err = "Please enter your email.";
        }

        // Check if password is empty
        if(empty($password)){
            $password_err = "Please enter your password.";
        }

        // Validate credentials
        if(empty($email_err) && empty($password_err)){

            // Prepare a select statement
            $sql = "SELECT id, name, lastName, email, password, type, address, zipcode_id, phone, activated FROM user WHERE email = ?";

            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = $email;

                $uporabnikEmail = $email;
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Store result
                    $stmt->store_result();
                    // Check if email exists, if yes then verify password
                    if($stmt->num_rows == 1) {
                        // Bind result variables
                        $stmt->bind_result($id, $name, $lastName, $email, $hashed_password, $type, $address, $zipcode_id, $phone, $activated);
                        if($stmt->fetch()){
                            if($type == 2) {
                                if(password_verify($password, $hashed_password)) {
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
                                            
                                            $message = $_SESSION;
                                            $code = 200;
                                        } else {
                                            $message = "$uporabnikEmail Not authorized user!";
                                            $code = 404;
                                        }
                                    } else {
                                        $message = "Wrong password!";
                                        $code = 404;
                                    }
                            } else {
                                $message = "Your account is not of type customer.";
                                $code = 404;
                            }
                        } else{
                            $message = "Something went wrong, try again.";
                            $code = 404;
                        }
                    } else{
                        // Display an error message if email doesn't exist
                        $message = "Account with this email does not exist.";
                        $code = 404;
                    }
                    // Close statement
                    $stmt->close();
                }
            }
            // Close connection
            $mysqli->close();
        }

        # blaze it
        echo ViewHelper::renderJSON($message, $code);
    }
}
