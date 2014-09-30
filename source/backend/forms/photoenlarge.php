<?
include_once("commentform.php");

if(isset($_GET["photo_id"])) {
    echo '<div class="largeContentBox"><div id="enlargedPhoto"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["photo_id"] . '.jpg" /></div>';
    echo getCommentsForm(4);
    echo '</div>';
}

?>