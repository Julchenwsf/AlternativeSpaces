<?
include_once("DBConnection.php");
include_once("../forms/voteform.php");

//Function to add photo to the database
function addPhoto($uploader, $title, $lat, $lng, $interests, $description) {
    $errors = array();
    $title = mysql_real_escape_string($title);                  //Run mysql_real_escape_string on all user input to avoid SQL injections
    $description = mysql_real_escape_string($description);

    if(!is_numeric($lat) || !is_numeric($lng)) {
        $errors[] = "Illegal latitude or longitude";           //Lat and lng should be plain numbers
    }

    if(!preg_match("/^[0-9 ]+$/", $interests)){
        $errors[] = "Illegal interest ids";                    //Interests string should be only space-separated numbers
    }

    if(strlen($title) < 4) {
        $errors[] = "Title too short (min 4 characters)";
    } else if(strlen($title) > 50) {
        $errors[] = "Title too long (max 50 characters)";
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $title)) {
        $errors[] = "Illegal characters in title (only letters, numbers and spaces)";
    }

    if(strlen($description) < 10) {
        $errors[] = "Description too short (min 10 characters)";
    } else if(strlen($description) > 1000) {
        $errors[] = "Description too long (max 1000 characters)";
    } else if (!preg_match("/^[a-zA-Z0-9 ,.!?()@\/]*$/", $description)) {
        array_push($errors, "Illegal characters in description");
    }

    $numInterests = sizeof(explode(" ", $interests));
    if($numInterests < 1) {
        $errors[] = "Enter at least 1 interest";
    }

    if(empty($errors)) mysql_query("INSERT INTO photos (location, photo_title, photo_uploader, interests, description, upload_time)
    VALUES ((GeomFromText('POINT(" . $lat . " " . $lng . ")')), '$title', '$uploader', '$interests', '$description', " . time() . ")") or array_push($errors, mysql_error());
    return (empty($errors)) ? mysql_insert_id() : $errors;
}


function removePhoto($id) {
    $id = mysql_real_escape_string($id);
    mysql_query("DELETE FROM photos WHERE photo_id=" . $id) or die(mysql_error());
    return true;
}


function getPhotoDetails($id) {
    if(!is_numeric($id)) return false;
    $result = mysql_query("SELECT * FROM photos WHERE photo_id='$id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}


//Parameters format:
//  Tags: +[interest_id] +[interest_id2]
//  Bounds: SW_lat SW_lng, NE_lat NE_lng
function searchPhotos($tags, $bounds, $page) {
    //If the tags is not set, ignore it from SQL (This way you get all possible interests), otherwise separate all interest IDs with plus signs (to get the AND relation)
    $tagsFilter = (strlen($tags) == 0) ? '' : "MATCH (interests) AGAINST ('" . mysql_real_escape_string("+" . str_replace(",", " +", $tags)) . "' IN BOOLEAN MODE) AND";

    $bounds = mysql_real_escape_string($bounds);    //Should probably be replaced with some fancy regex
    $page = intval($page);
    $result = mysql_query("SELECT photo_id, photo_title FROM photos
    WHERE " . $tagsFilter ." MBRContains(GeomFromText('LINESTRING(" . $bounds . ")'), photos.location)
    ORDER BY LOG10(ABS(vote_up - vote_down) + 1) * SIGN(vote_up - vote_down) + (upload_time / 300000) DESC
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
        $res = searchPhotos($_GET["interests"], $_GET["boxloc"], $_GET["page"]);    //Do the search
        foreach($res as &$row) {
            //For each search result, pack it nicely into its HTML representation. Currently a simple img inside div
            echo '<div class="contentBox" data-content-id="' . $row["photo_id"] . '">
                    <div class="contentWrapper">
                        <div class="bg"></div>

                        <div class="contentTitle">' . $row["photo_title"] . '</div>
                        <div class="contentContent center"><img src="http://org.ntnu.no/cdpgroup4/images/thumb/' . $row["photo_id"] . '.jpg" /></div>
                        <div class="contentStats">'. getVoter("photos", $row["photo_id"]) . '</div>
                    </div>
                </div>';
        }
    }
}
?>