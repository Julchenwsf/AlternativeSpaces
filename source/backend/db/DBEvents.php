<?php
include('DBConnection.php');


function addEvent($eventname, $location, $Day, $Month, $year, $hour, $Minutes) {
    $errors = array();
    $eventName = mysql_real_escape_string(trim($eventName));
    $location = mysql_real_escape_string(trim($location));
    $day = mysql_real_escape_string($day);
    $month = mysql_real_escape_string($month);
    $year = mysql_real_escape_string($year);
    $hour = mysql_real_escape_string($hour);
    $minutes = mysql_real_escape_string($minutes);

    if(empty($eventname)) {
        array_push($errors, "eventname cannot be left blank");
    } else {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $username)) {
            array_push($errors, "Username can only contain letters, numbers and whitespace");
        } else if(usernameExists($username)) {
            array_push($errors, $username . " is already taken. Select another username");
        }
    }

    if(empty($location)) {
        array_push($errors, "location cannot be left blank");
    }

    if(empty($day)) {
        array_push($errors, "Day cannot be left blank");
    }

    if(empty($month)) {
        array_push($errors, "Month cannot be left blank");
    }

    if(empty($year)) {
        array_push($errors, "year cannot be left blank");
    }

    if(empty($hour)) {
            array_push($errors, "hour cannot be left blank");
        }

        if(empty($minutes)) {
                array_push($errors, "minutes cannot be left blank");
            }


    if(empty($errors)) mysql_query("INSERT INTO events (event_name, location, day, month, year, hour, minutes) VALUES ('$eventName', '$location', '$day', '$month', '$year', '$hour', '$minutes')") or array_push($errors, mysql_error());
    return $errors;
}


function fetchEvent($eventname, $event_id) {
    $eventname = mysql_real_escape_string($eventname);
    $event_id = mysql_real_escape_string($event_id);

    $result = mysql_query("SELECT * FROM events WHERE username='$eventname' AND event_id='$event_id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}


function eventnameExists($eventname) {
    $eventname = mysql_real_escape_string($eventname);
    $query = mysql_query("SELECT eventname FROM events WHERE eventname='" . $eventname . "'") or die(mysql_error());
    return mysql_num_rows($query) != 0;
}

?>