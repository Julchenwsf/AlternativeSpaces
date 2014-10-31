<?php
include('DBConnection.php');
include('DBInterests.php');
include_once("../forms/voteform.php");


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
        } else if(strlen($event_name) > 60) {
            array_push($errors, "Event title too long (max 60 characters)");
        }
    }

    if(empty($description)) {
        array_push($errors, "Description cannot be left blank");
    } else if (!preg_match("/^[a-zA-Z0-9 ,.!?()@\\/]*$/", $description)) {
        array_push($errors, "Illegal characters in description");
    }

    if(!is_numeric($lat) || !is_numeric($lng)) {
        array_push($errors, "Illegal location");
    }

    if(empty($time) || !is_numeric($time)) {
        array_push($errors, "Illegal date/time");
    }

    if(!is_numeric($numPeople)) {
        array_push($errors, "Number of people must be a number");
    } else if(intval($numPeople) < 1 || intval($numPeople) > 1000) {
        array_push($errors, "Illegal number of people [1, 1000]");
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
    return mysql_fetch_assoc($result);
}


function voteEvent($id, $up, $down) {
    mysql_query("UPDATE events SET vote_up=vote_up+$up, vote_down=vote_down+$down WHERE event_id='$id'");
    $result = mysql_query("SELECT vote_up, vote_down FROM events WHERE event_id='$id'");
    $row = mysql_fetch_assoc($result);
    return array($row["vote_up"], $row["vote_down"]);
}


function eventTimeFormat($t){
    if(date('d')==date('d', $t)) return "Today at " . date('H:i', $t);
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
    $result = mysql_query("SELECT event_id, event_name, vote_up, vote_down, description, event_time, interests, X(location) AS latitude, Y(location) AS longitude FROM events
    WHERE " . $tagsFilter ." MBRContains(GeomFromText('LINESTRING(" . $bounds . ")'), events.location) AND event_time+86400 > UNIX_TIMESTAMP()
    ORDER BY LOG10(ABS(vote_up - vote_down) + 1) * SIGN(vote_up - vote_down) DESC
    LIMIT " . 20*$page . ", 20") or die(mysql_error());
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
            $interests = explode(" ", $row["interests"]);
            $interestIcons="";
            foreach($interests as &$interest){
                $data = getInterest($interest);
                $interestIcons .= '<li class="token-input-token"><img src="img/interests/' .$data["interest_icon"] .'"/><p class="hidden">'.$data["interest_name"].'</p></li>';
            }

            echo '<div class="contentBox">
                 <div class="contentWrapper">
                     <div class="bg"></div>

                     <div class="contentClickArea" data-content-id="' . $row["event_id"] . '">
                         <div class="contentTitle">' . (strlen($row["event_name"])>26 ? substr($row["event_name"],0,23) . '...' : $row["event_name"]) . '</div>
                         <div class="contentContent eventContent">
                             <div class= "contentBoxInfo">Description</div><div class="contentBoxDescription">' . (strlen($row["description"])>70 ? substr($row["description"],0,70) . '...' : $row["description"]) . '<hr>
                             <ul class="token-input-list">'. $interestIcons . '</ul></div>
                             <div class="eventTime">' . eventTimeFormat($row["event_time"]) .' </div>
                         </div>
                     </div>

                     <div class="contentStats">'. getVoter("events", $row["event_id"], $row["vote_up"], $row["vote_down"]) . '</div>
                 </div>
             </div>';
        }
    }
}

?>