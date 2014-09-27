<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once('DBUsers.php');

//************************************************************************************************************
// $username=$_POST['username'];
// $password=$_POST['password'];

$username = "Julchen";
$password = "test";
//************************************************************************************************************

print_r(checkLogin("Julchen", "test")) . "\n"; //Is valid
print_r(checkLogin("Julchen", "test123")) . "\n"; //Wrong password
print_r(checkLogin("User", "test")) . "\n"; //Wrong username
print_r(checkLogin("valerij", "test123")) . "\n"; //Is valid

?>

