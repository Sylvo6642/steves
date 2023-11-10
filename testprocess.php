<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and set to empty values
    $firstname = $lastname = $email = $phone = $message = $ip_address = "";

    // Check if fields are empty and sanitize input
    if (!empty($_POST["firstname"])) {
        $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
    }

    if (!empty($_POST["lastname"])) {
        $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    }

    if (!empty($_POST["email"])) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
        }
    }

    if (!empty($_POST["phone"])) {
        $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    }

    if (!empty($_POST["message"])) {
        $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);
    }

    // Get IP address
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    // Check if IP address is not empty
    if (!empty($ip_address)) {
        // Database connection
        $dbconn = pg_connect("host=localhost dbname=STEVE user=postgres password=loveenarobin")
            or die('Could not connect: ' . pg_last_error());

        // Check if the same IP address has made a submission in the last 1 minute
        $query = "SELECT * FROM questions WHERE ip_address = '$ip_address' AND submission_time > NOW() - INTERVAL '1 minute'";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

        if (pg_num_rows($result) > 0) {
            echo "Please wait another 1 minute to submit again.";
        } else {
            // Prepare and execute the query
            $query = "INSERT INTO questions (firstname, lastname, email, phone, message, ip_address, submission_time) VALUES ('$firstname', '$lastname', '$email', '$phone', '$message', '$ip_address', NOW())";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());

            // Redirect to index.html
            header("Location: index.html");
        }

        // Free resultset and close connection
        pg_free_result($result);
        pg_close($dbconn);
    } else {
        echo "IP address is empty";
    }
}
?>
