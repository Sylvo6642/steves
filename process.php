<?php
$host = "localhost";
$user = "postgres";
$pass = "loveenarobin";
$db = "STEVE";
$con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");

if(!$con){
    echo "Error: Unable to open database\n";
} else {

    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $query = "INSERT INTO questions (firstname, lastname, email, phone, message) VALUES ('$firstname',' $lastname ',' $email ',' $phone',' $message')";
    $result = pg_query($con, $query);
    header("Location: index.html");
        }
pg_close($con);

?>