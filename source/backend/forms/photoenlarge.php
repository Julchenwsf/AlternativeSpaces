<?
include_once("../db/DBComments.php");

if(isset($_GET["photo_id"])) {
    echo '<div class="largeContentBox"><div id="enlargedPhoto"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["photo_id"] . '.jpg" /></div>
    <div id="commentArea">';

    $comments = getComments();
    foreach($comments as $c) {
        showComment($c);
    }

    echo '</div>
    <input type="button" class="waveButtonMain" value="Add a comment" onclick="addComment()" /></div>';
}

?>