<?php
include('DBConnection.php');
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$mname=$_POST['mname'];
$address=$_POST['address'];
$contact=$_POST['contact'];
$pic=$_POST['pic'];
$username=$_POST['username'];
$password=$_POST['password'];
mysql_query("INSERT INTO users(first_name, last_name, gender, address, contact, picture, username, password)VALUES('$fname', '$lname', '$mname', '$address', '$contact', '$pic', '$username', '$password')");
//header("location: index.php?remarks=success");
mysql_close($con);
?>