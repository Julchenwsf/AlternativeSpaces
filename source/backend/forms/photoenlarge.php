<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/forms/commentform.php");
include_once($path . "backend/db/DBPhotos.php");

if(isset($_GET["photo_id"])) {
    $photoData = getPhotoDetails($_GET["photo_id"]);
    echo '<div class="largeContentBox"><div id="enlargedPhoto"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["photo_id"] . '.jpg" /></div>';
    echo "<h3>" . $photoData["photo_title"] . "</h3>";
    echo "<p>" . $photoData["description"]. "</p>";
    echo getCommentsForm("p" . $_GET["photo_id"]);
    echo '</div>';
}

?>