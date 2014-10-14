<?php
include('DBConnection.php');


function addEvent($event_name, $location, $day, $month, $year, $hour, $minutes, $no of people invited, $description) {
    $errors = array();
    $event_name = mysql_real_escape_string(trim($event_name));
    $location = mysql_real_escape_string(trim($location));
    $day = mysql_real_escape_string($day);
    $month = mysql_real_escape_string($month);
    $year = mysql_real_escape_string($year);
    $hour = mysql_real_escape_string($hour);
    $minutes = mysql_real_escape_string($minutes);
    $no of people invited = mysql_real_escape_string($minutes);
    $description = mysql_real_escape_string(trim($description));

    if(empty($event_name)) {
        array_push($errors, "event_name cannot be left blank");
    } else {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $event_name)) {
            array_push($errors, "event_name can only contain letters, numbers and whitespace");
        } else if(event_nameExists($username)) {
            array_push($errors, $event_name . " is already taken. Select another event_name");
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


function fetchEvent($event_name, $event_id) {
    $event_name = mysql_real_escape_string($event_name);
    $event_id = mysql_real_escape_string($event_id);

    $result = mysql_query("SELECT * FROM events WHERE event_name='$event_name' AND event_id='$event_id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}


function event_nameExists($event_name) {
    $event_name = mysql_real_escape_string($event_name);
    $query = mysql_query("SELECT event_name FROM events WHERE event_name='" . $event_name . "'") or die(mysql_error());
    return mysql_num_rows($query) != 0;
}

function eventTimeFormat($t){
    if(date('d')==date('d', $t)) return date('H:i', $t);
    return date('j. M Y \a\t H:i', $t);
}


?>