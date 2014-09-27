<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include('DBConnection.php');

//************************************************************************************************************
// $username=$_POST['username'];
// $password=$_POST['password'];

$username = "XXX";
$password = "xxx";
//************************************************************************************************************
// testing ob username or password or both are empty
if ($username == "" or $password=="") {
    echo "username or password ist empty";
} else {
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);

    $result = mysql_query("SELECT username, password FROM users WHERE username like '$username' AND password='$password'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    $db_username = $row['username'];
    $db_password = $row['password'];

    if ($db_username == "") {
        echo "username or password incorrect";
    } else {
        if ($db_password == $password) {
            //echo "login successful";
            // Setting of session variables
            $_SESSION['user'] = $username;

            echo $_SESSION['user'];

            // ****************** not in general******************
           $target = "http://folk.ntnu.no/juliasch/git/AlternativeSpaces/source/";
           header("Location: $target");
           exit;

        } else {
            echo "username or password incorrect";
        }
    }

}

?>

