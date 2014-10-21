<?php
include('DBConnection.php');


function addEvent($event_name, $location, $Day, $Month, $Year, $no_of_People, $description, $Hour, $Min) {
    $errors = array();
    $event_name = mysql_real_escape_string(trim($event_name));
    $location = mysql_real_escape_string(trim($location));
    $Day = mysql_real_escape_string($Day);
    $Month = mysql_real_escape_string($Month);
    $Year = mysql_real_escape_string($Year);
    $no_of_People = mysql_real_escape_string($no_of_People);
    $description = mysql_real_escape_string(trim($description));
    $Hour = mysql_real_escape_string($Hour);
    $Min = mysql_real_escape_string($Min);

    if(empty($event_name)) {
        array_push($errors, "event_name cannot be left blank");
    } else {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $event_name)) {
            array_push($errors, "event_name can only contain letters, numbers and whitespace");
        }
    }

    if(empty($location)) {
        array_push($errors, "location cannot be left blank");
    }

    if(empty($Day)) {
        array_push($errors, "Day cannot be left blank");
    }

    if(empty($Month)) {
        array_push($errors, "Month cannot be left blank");
    }

    if(empty($Year)) {
        array_push($errors, "year cannot be left blank");
    }

    if(empty($Hour)) {
        array_push($errors, "Hour cannot be left blank");
    }

    if(empty($Min)) {
        array_push($errors, "Min cannot be left blank");
    }

    $unixtime = strtotime($Year . "-" . $Month . "-" . $Day . " " . $Hour . ":" . $Min . ":00");
    if(empty($errors)) mysql_query("INSERT INTO events (event_name, location, no_of_people, description, event_time) VALUES ('$event_name', '$location', '$no_of_People', '$description', '$unixtime')") or array_push($errors, mysql_error());
    return $errors;
}


function fetchEvent($event_name, $event_id) {
    $event_name = mysql_real_escape_string($event_name);
    $event_id = mysql_real_escape_string($event_id);

    $result = mysql_query("SELECT * FROM events WHERE event_name='$event_name' AND event_id='$event_id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}



function eventTimeFormat($t){
    if(date('d')==date('d', $t)) return date('H:i', $t);
    return date('j. M Y \a\t H:i', $t);
}

?>