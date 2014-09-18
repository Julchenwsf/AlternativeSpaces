<?
include_once("DBConnection.php");

function addPhoto($id, $title, $lat, $lng, $interests) {
    $title = mysql_real_escape_string($title);
    if(!is_numeric($lat) || !is_numeric($lng)) return false;
    if(!preg_match("/^[0-9 ]+$/", $interests)) return false;
    mysql_query("INSERT INTO photos (photo_id, location, photo_title, interests, upload_time, rating) VALUES (".$id.", (GeomFromText('POINT(" . $lat . " " . $lng . ")')), '" . $title . "', '" . $interests . "', " . (time() - rand(0,  864000)) . ", " . rand(50, 100) . ")")
    or die(mysql_error());
    return true;
}

//Parameters format:
//  Tags: +[interest_id] +[interest_id2]
//  Bounds: SW_lat SW_lng, NE_lat NE_lng
function searchPhotos($tags, $bounds, $page) {
    $tagsFilter = (strlen($tags) == 0) ? '' : "MATCH (interests) AGAINST ('" . mysql_real_escape_string("+" . str_replace(",", " +", $tags)) . "' IN BOOLEAN MODE) AND";

    $bounds = mysql_real_escape_string($bounds);
    $page = intval($page);
    $result = mysql_query("SELECT photo_id, photo_title, interests FROM photos WHERE " . $tagsFilter ." MBRContains(GeomFromText('LINESTRING(" . $bounds . ")'), photos.location) ORDER BY rating DESC LIMIT " . 20*$page . ", 20") or die(mysql_error());
    $return_arr = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($return_arr, $row);
    }
    return $return_arr;
}


if(isset($_GET["search"]) && $_GET["search"] == "2D") {
    if(! isset($_GET["boxloc"])) echo "Missing bounding box coordinates!";
    else if(! isset($_GET["interests"])) echo "Missing interests!";
    else if(! isset($_GET["page"])) echo "Missing page!";
    else {
        $res = searchPhotos($_GET["interests"], $_GET["boxloc"], $_GET["page"]);
        foreach($res as &$row) {
            echo '<div class="photoBox"><img src="http://org.ntnu.no/cdpgroup4/images/thumb/' . $row["photo_id"] . '.jpg" /></div>';
        }
    }
}


if(isset($_GET["upload"])) {
    if(addPhoto($_GET["id"], $_GET["title"], $_GET["lat"], $_GET["lng"], $_GET["interests"])) {
        echo "OK";
    } else {
        echo "FAIL";
    }
}

?>