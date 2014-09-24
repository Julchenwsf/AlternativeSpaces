<?
include_once("backend/PageBuilder.php");

/*
To log in go to log.php?in=[your username]
To log out go to log.php?out */
if(isset($_GET["in"])) {
    $_SESSION['user'] = $_GET["in"];
    echo "Logged in as " . $_GET["in"];
} else if(isset($_GET["out"])) {
    session_destroy();
    echo "Logged out!";
}

?>