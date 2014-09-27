<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

echo "This is the backend for the login form";

include('DBConnection.php');

//************************************************************************************************************
// $username=$_POST['username'];
// $password=$_POST['password'];

$username = "Julchen";
$password = "julia";
//************************************************************************************************************
// testing ob username or password or both are empty
if ($username == "" or $password=="")
{
    echo "username or password ist empty";
}
else
{


// Escaping of user input - Security Aspect (XSS)
$username = htmlspecialchars($username);
$password = htmlspecialchars($password);

//Connection to DB to check username and the password belonging to them.
// it doesn´t matter if using upper- or lower case

$sql= "(SELECT * from users WHERE username like '$username')";
$result = mysql_query($sql, $conn) or die(mysql_error());
while($row = mysql_fetch_row($result))
{
    $dbusername = $row[1];
    $dbpassword = $row[2];
}

if ($dbusername == "") // username is incorrect
{
    echo "username or password incorrect";
}
else // check password
{
    if ($dbpassword == $password)
    {
        echo "login successful";
        // Setting of session variables
        $_SESSION['user'] = $username;

        echo $_SESSION['user'];

        // redirect to front page (Map)
        //$target = "http://www.html-php-mysql.de";

        // ****************** not in general******************
       $target = "http://folk.ntnu.no/juliasch/git/AlternativeSpaces/source/";
       header("Location:$target");
       exit;

    }
    else
    {
        echo "username or password incorrect";
    }
}
}

?>

