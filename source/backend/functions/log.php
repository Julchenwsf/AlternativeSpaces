<?
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/db/DBUsers.php");
session_start();


function login($username, $password) {
    $status = checkLogin($username, $password);

    if(empty($status)) return $status;
    $_SESSION["username"] = $status["username"];
    $_SESSION["first_name"] = $status["first_name"];
    $_SESSION["last_name"] = $status["last_name"];

    return $status;
}


function loginPlainText($username, $password) {
    return login($username, sha1(sha1($password)));
}


function logout() {
    session_destroy();
}


function isLoggedIn() {
    if(isset($_SESSION["username"])) {
        $_SESSION["username"] = $_SESSION["username"];
        return true;
    }
    return false;
}

if(isset($_GET["out"])) {
    logout();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>