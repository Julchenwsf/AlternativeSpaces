<?php
include('DBConnection.php');


function addUser($username, $password, $firstName, $lastName, $gender) {
    $errors = array();
    $firstName = mysql_real_escape_string(trim($firstName));
    $lastName = mysql_real_escape_string(trim($lastName));
    $username = mysql_real_escape_string(trim($username));
    $password = sha1($password);
    $gender = mysql_real_escape_string($gender);

    if(empty($username)) {
        array_push($errors, "Username cannot be left blank");
    } else {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $username)) {
            array_push($errors, "Username can only contain letters, numbers and whitespace");
        } else if(usernameExists($username)) {
            array_push($errors, $username . " is already taken. Select another username");
        }
    }

    if(empty($password)) {
        array_push($errors, "Password cannot be left blank");
    } else if(strlen($password) < 6) {
        array_push($errors, "Password must be at least 6 characters long");
    }

    if(empty($firstName)) {
        array_push($errors, "First name cannot be left blank");
    }

    if(empty($lastName)) {
        array_push($errors, "Last name cannot be left blank");
    }

    if($gender != "male" and $gender != "female") { //Sigh...
        array_push($errors, "Gender must be either male or female");
    }

    if(empty($errors)) mysql_query("INSERT INTO users (first_name, last_name, gender, username, password) VALUES ('$firstName', '$lastName', '$gender', '$username', '$password')") or array_push($errors, mysql_error());
    return $errors;
}


function checkLogin($username, $password) {
    $username = mysql_real_escape_string($username);
    $password = sha1($password);

    $result = mysql_query("SELECT * FROM users WHERE username='$username' AND password='$password'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}


function usernameExists($username) {
    $username = mysql_real_escape_string($username);
    $query = mysql_query("SELECT username FROM users WHERE username='" . $username . "'") or die(mysql_error());
    return mysql_num_rows($query) != 0;
}

?>