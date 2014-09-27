<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include('DBConnection.php');

//************************************************************************************************************
// $username=$_POST['username'];
// $password=$_POST['password'];

$username = "Julchen";
$password = "julia";
//************************************************************************************************************
// testing ob username or password or both are empty
if ($username == "" or $password=="") {
    echo "username or password ist empty";
} else {
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);

    $result = mysql_query("SELECT * FROM users WHERE username='$username' AND password='$password'") or die(mysql_error());
    while($row = mysql_fetch_row($result)) {
        $dbusername = $row[1];
        $dbpassword = $row[2];
    }

    if ($dbusername == "") {
        echo "username or password incorrect";
    } else {
        if ($dbpassword == $password) {
            echo "login successful";
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

