<?
include_once("DBConnection.php");

//Function to add photo to the database
function addPhoto($id, $title, $lat, $lng, $interests) {
    $title = mysql_real_escape_string($title);                  //Run mysql_real_escape_string on all user input to avoid SQL injections
    if(!is_numeric($lat) || !is_numeric($lng)) return false;    //Lat and lng should be plain numbers
    if(!preg_match("/^[0-9 ]+$/", $interests)) return false;    //Interests string should be only space-separated numbers
    mysql_query("INSERT INTO photos (photo_id, location, photo_title, interests, upload_time, rating) VALUES (".$id.", (GeomFromText('POINT(" . $lat . " " . $lng . ")')), '" . $title . "', '" . $interests . "', " . (time() - rand(0,  864000)) . ", " . rand(50, 100) . ")")
    or die(mysql_error());
}


//Parameters format:
//  Tags: +[interest_id] +[interest_id2]
//  Bounds: SW_lat SW_lng, NE_lat NE_lng
function searchPhotos($tags, $bounds, $page) {
    //If the tags is not set, ignore it from SQL (This way you get all possible interests), otherwise separate all interest IDs with plus signs (to get the AND relation)
    $tagsFilter = (strlen($tags) == 0) ? '' : "MATCH (interests) AGAINST ('" . mysql_real_escape_string("+" . str_replace(",", " +", $tags)) . "' IN BOOLEAN MODE) AND";

    $bounds = mysql_real_escape_string($bounds);    //Should probably be replaced with some fancy regex
    $page = intval($page);
    $result = mysql_query("SELECT photo_id, photo_title, interests FROM photos WHERE " . $tagsFilter ." MBRContains(GeomFromText('LINESTRING(" . $bounds . ")'), photos.location) ORDER BY rating DESC LIMIT " . 20*$page . ", 20") or die(mysql_error());
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
            echo '<div class="photoBox"><img src="http://org.ntnu.no/cdpgroup4/images/thumb/' . $row["photo_id"] . '.jpg" /></div>';
        }
    }
}

//This is used to populate the database via my Python script, ignore.
if(isset($_GET["upload"])) {
    if(addPhoto($_GET["id"], $_GET["title"], $_GET["lat"], $_GET["lng"], $_GET["interests"])) {
        echo "OK";
    } else {
        echo "FAIL";
    }
}

?>