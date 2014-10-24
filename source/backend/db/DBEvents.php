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
        } else if(strlen($event_name) > 30) {
            array_push($errors, "Event title too long (max 30 characters)");
        }
    }

    if(empty($description)) {
        array_push($errors, "Description cannot be left blank");
    } else if (!preg_match("/^[a-zA-Z0-9 ,.!?()@\/]*$/", $description)) {
        array_push($errors, "Illegal characters in description");
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
    return (empty($errors)) ? mysql_insert_id() : $errors;
}


function fetchEvent($event_id) {
    $event_id = mysql_real_escape_string($event_id);

    $result = mysql_query("SELECT *, X(location) AS latitude, Y(location) AS longitude FROM events WHERE event_id='$event_id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}



function eventTimeFormat($t){
    if(date('d')==date('d', $t)) return date('H:i', $t);
    return date('j. M y \a\t H:i', $t);
}

//Parameters format:
//  Tags: +[interest_id] +[interest_id2]
//  Bounds: SW_lat SW_lng, NE_lat NE_lng
function searchEvents($tags, $bounds, $page) {
    //If the tags is not set, ignore it from SQL (This way you get all possible interests), otherwise separate all interest IDs with plus signs (to get the AND relation)
    $tagsFilter = (strlen($tags) == 0) ? '' : "MATCH (interests) AGAINST ('" . mysql_real_escape_string("+" . str_replace(",", " +", $tags)) . "' IN BOOLEAN MODE) AND";

    $bounds = mysql_real_escape_string($bounds);    //Should probably be replaced with some fancy regex
    $page = intval($page);
    $result = mysql_query("SELECT event_id, event_name, description FROM events WHERE " . $tagsFilter ." MBRContains(GeomFromText('LINESTRING(" . $bounds . ")'), events.location) ORDER BY rating DESC LIMIT " . 20*$page . ", 20") or die(mysql_error());
    $return_arr = array();

    //Format the result to be an array of dictionaries for each row/result.
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($return_arr, $row);
    }
    return $return_arr;
}


//If the search argument is set to "2D"...
if(isset($_GET["search"]) && $_GET["search"] == "2D") {
    if(! isset($_GET["boxloc"])) echo "Missing bounding box coordinates!";  //Make sure that the bounding box coordinates are specified
    else if(! isset($_GET["interests"])) echo "Missing interests!";         //Also the interests are specified
    else if(! isset($_GET["page"])) echo "Missing page!";                   //And finally the page number is specified
    else {
        $res = searchEvents($_GET["interests"], $_GET["boxloc"], $_GET["page"]);    //Do the search
        foreach($res as &$row) {
            //For each search result, pack it nicely into its HTML representation. Currently a simple img inside div
            echo '<div class="contentBox" data-event-id="' . $row["event_id"] . '"><div id = eventBox><h2>' . $row["event_name"] .'</h2><br>' . $row["description"] . '<br><a href="#" class="likeButton"></a><a href="#" class="dislikeButton"></a></div></div>';
        }
    }
}

?>