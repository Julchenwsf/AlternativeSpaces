<?
include_once("DBConnection.php");

function addPhoto($id, $title, $lat, $lng, $interests) {
    $title = mysql_real_escape_string($title);
    if(!is_numeric($lat) || !is_numeric($lng)) return false;
    if(!preg_match("/^[0-9 ]+$/", $interests)) return false;
    mysql_query("INSERT INTO photos (photo_id, location, photo_title, interests, upload_time, rating) VALUES (".$id.", (GeomFromText('POINT(" . $lat . " " . $lng . ")')), '" . $title . "', '" . $interests . "', " . (time() - rand(0,  864000)) . ", " . rand(0, 10000)/100.0 . ")")
    or die(mysql_error());
    return true;
}

//Parameters format:
//  Tags: +[interest_id] +[interest_id2]
//  Bounds: SW_lat SW_lng, NE_lat NE_lng
function searchPhotos($tags, $bounds) {
    $tags = mysql_real_escape_string($tags);
    $bounds = mysql_real_escape_string($bounds);
    $result = mysql_query("SELECT photo_id, photo_title, interests FROM photos WHERE MBRContains(GeomFromText('LINESTRING(" . $bounds . ")'), photos.location) LIMIT 20") or die(mysql_error());
    $return_arr = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($return_arr, $row);
    }
    return $return_arr;
}


if(isset($_GET["search"]) && $_GET["search"] == "2D") {
    if(! isset($_GET["boxloc"])) echo "Missing bounding box coordinates!";
    else if(! isset($_GET["interests"])) echo "Missing interests!";
    else {
        $res = searchPhotos($_GET["interests"], $_GET["boxloc"]);
        foreach($res as &$row) {
            echo '<div class="photoBox"><img src="http://org.ntnu.no/cdpgroup4/images/thumb/' . $row["photo_id"] . '.jpg" /></div>';
        }
    }
}


if(isset($_GET["upload"])) {
    echo addPhoto($_GET["id"], $_GET["title"], $_GET["lat"], $_GET["lng"], $_GET["interests"]);
}

?>