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
mysql_query("INSERT INTO users(first_name, last_name, gender, picture, username, password)VALUES('$fname', '$lname', '$mname', '$pic', '$username', '$password')") or die(mysql_error());
//header("location: index.php?remarks=success");
?>