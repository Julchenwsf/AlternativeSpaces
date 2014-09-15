<?
include_once("DBConnection.php");

function addPhoto($title, $lat, $lng) {
    $title = mysql_real_escape_string($title);
    mysql_query("INSERT INTO photos (location, photo_title, upload_time, rating) VALUES ((GeomFromText('POINT(" . $lat . " " . $lng . ")')), '" . $title . "', " . (time() - rand(0,  864000)) . ", " . rand(0, 10000)/100.0 . ")")
    or die(mysql_error());
}

?>