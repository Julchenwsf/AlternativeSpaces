<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/forms/commentform.php");

if(isset($_GET["photo_id"])) {
    echo '<div class="largeContentBox"><div id="enlargedPhoto"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["photo_id"] . '.jpg" /></div>';
    echo getCommentsForm(4);
    echo '</div>';
}

?>