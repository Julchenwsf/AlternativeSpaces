<?
include_once("../db/DBComments.php");

function getCommentsForm($id) {
    echo '<div id="commentArea" data-thread-id="' . $id . '">';

    $comments = getComments();
    foreach($comments as $c) {
        showComment($c);
    }

    echo '</div>';
    if(isLoggedIn())
        echo '<input type="button" class="waveButtonMain" value="Add a comment" onclick="addComment()" />';
}


if(isset($_POST["comment"])) {
    $arr = array("comment_id" => 4, "comment_parent" => $_POST["parent"], "username" => $_SESSION["username"], "comment" => $_POST["comment"], "time" => time());
    showComment($arr);
}


?>