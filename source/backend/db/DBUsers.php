<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


echo "test";

include('DBConnection.php');

$fname=$_POST['fname'];
$lname=$_POST['lname'];
$address=$_POST['address'];
$gender=$_POST['gender'];
$pic=$_POST['pic'];
$username=$_POST['username'];
$password=$_POST['password'];
$address = $_POST['address'];

 mysql_query("INSERT INTO users(first_name, last_name, gender, picture, username, password, address)VALUES('$fname', '$lname', '$gender', '$pic', '$username', '$password', '$address')") or die(mysql_error());

//header("location: index.php?remarks=success");

?>