<?
session_start();

function login($username, $firstName, $lastName) {
    $_SESSION["username"] = $username;
    $_SESSION["first_name"] = $firstName;
    $_SESSION["last_name"] = $lastName;
}

function logout() {
    session_destroy();
}


function isLoggedIn() {
    return isset($_SESSION["username"]);
}

if(isset($_GET["out"])) {
    logout();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>