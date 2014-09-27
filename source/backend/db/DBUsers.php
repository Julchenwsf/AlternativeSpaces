<?php
include('DBConnection.php');
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$username = $_POST['username'];
$password = $_POST['password'];
$gender = $_POST['gender'];


mysql_query("INSERT INTO users (first_name, last_name, gender, username, password) VALUES ('$fname', '$lname', '$gender', '$username', '$password')") or die(mysql_error());
header("location: ../../index.php");
?>