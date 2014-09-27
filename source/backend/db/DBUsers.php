<?php
include('DBConnection.php');


if(isset($_POST['regformSubmit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    //check if user allready exist

    // insert User in DB
    mysql_query("INSERT INTO users (first_name, last_name, gender, username, password) VALUES ('$fname', '$lname', '$gender', '$username', '$password')") or die(mysql_error());
    header("location: ../../index.php");
}

function checkLogin($username, $password) {
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);

    $result = mysql_query("SELECT * FROM users WHERE username='$username' AND password='$password'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}

?>