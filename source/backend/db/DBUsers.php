<?php
include('DBConnection.php');


function addUser($username, $password, $firstName, $lastName, $gender) {
    $errors = array();
    $firstName = mysql_real_escape_string(trim($firstName));
    $lastName = mysql_real_escape_string(trim($lastName));
    $username = mysql_real_escape_string(trim($username));
    $gender = mysql_real_escape_string($gender);

    if(empty($username)) {
        array_push($errors, "Username cannot be left blank");
    } else {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $username)) {
            array_push($errors, "Username can only contain letters, numbers and whitespace");
        } else if(strlen($username) > 20) {
            array_push($errors, "Username is too long (max 20 characters)");
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
    } else if(strlen($firstName) > 20) {
        array_push($errors, "First name is too long (max 20 characters)");
    } else if (!preg_match("/^[a-zA-Z -]*$/", $firstName)) {
        array_push($errors, "Invalid first name (only letters, dash and spaces)");
    }

    if(empty($lastName)) {
        array_push($errors, "Last name cannot be left blank");
    } else if(strlen($lastName) > 30) {
        array_push($errors, "Last name is too long (max 30 characters)");
    } else if (!preg_match("/^[a-zA-Z -]*$/", $lastName)) {
        array_push($errors, "Invalid last name (only letters, dash and spaces)");
    }

    if($gender != "male" and $gender != "female") { //Sigh...
        array_push($errors, "Gender must be either male or female");
    }

    $password = sha1($password);
    if(empty($errors)) mysql_query("INSERT INTO users (first_name, last_name, gender, username, password) VALUES ('$firstName', '$lastName', '$gender', '$username', '$password')") or array_push($errors, mysql_error());
    return $errors;
}


function checkLogin($username, $password) {
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);

    $result = mysql_query("SELECT * FROM users WHERE username='$username'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return (sha1($row["password"]) == $password) ? $row : array();
}


function usernameExists($username) {
    $username = mysql_real_escape_string($username);
    $query = mysql_query("SELECT username FROM users WHERE username='" . $username . "'") or die(mysql_error());
    return mysql_num_rows($query) != 0;
}

?>