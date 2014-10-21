<?php
include('DBConnection.php');


function addEvent($creator, $event_name, $description, $interests, $lat, $lng, $numPeople, $time) {
    $errors = array();
    $event_name = mysql_real_escape_string(trim($event_name));
    $numPeople = mysql_real_escape_string($numPeople);
    $description = mysql_real_escape_string(trim($description));
    $interests = str_replace(",", " ", $interests);

    if(empty($event_name)) {
        array_push($errors, "Event title cannot be left blank");
    } else {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $event_name)) {
            array_push($errors, "Event title can only contain letters, numbers and whitespace");
        } else if(strlen($event_name) > 50) {
            array_push($errors, "Event title too long (max 50 characters)");
        }
    }

    if(empty($description)) {
        array_push($errors, "Description cannot be left blank");
    }

    if(!is_numeric($lat) || !is_numeric($lng)) {
        array_push($errors, "Illegal location");
    }

    if(empty($time)) {
        array_push($errors, "Illegal date/time");
    }

    if(!is_numeric($numPeople)) {
        array_push($errors, "Number of people must be a number");
    }

    if(empty($interests)) {
        array_push($errors, "Enter at least 1 interest");
    } elseif (!preg_match("/^[0-9 ]*$/", $interests)) {
        array_push($errors, "Illegal interests");
    }

    if(empty($errors)) mysql_query("INSERT INTO events (event_creator, event_name, interests, location, no_of_people, description, event_time) VALUES ('$creator', '$event_name', '$interests', (GeomFromText('POINT(" . $lat . " " . $lng . ")')), '$numPeople', '$description', '$time')") or array_push($errors, mysql_error());
    return $errors;
}


function fetchEvent($event_id) {
    $event_id = mysql_real_escape_string($event_id);

    $result = mysql_query("SELECT * FROM events WHERE event_id='$event_id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}



function eventTimeFormat($t){
    if(date('d')==date('d', $t)) return date('H:i', $t);
    return date('j. M Y \a\t H:i', $t);
}

?>