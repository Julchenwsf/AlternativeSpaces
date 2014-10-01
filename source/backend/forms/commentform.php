<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/db/DBComments.php");

function getCommentsForm($id) {
    $temp = '<div id="commentArea" data-thread-id="' . $id . '">';

    $comments = getComments();
    foreach($comments as $c) {
        $temp .= showComment($c);
    }

    $temp .= '</div>';
    if(isLoggedIn())
        $temp .= '<input type="button" class="waveButtonMain" value="Add a comment" onclick="addComment()" />';
    return $temp;
}


if(isset($_POST["comment"])) {
    $arr = array("comment_id" => 4, "comment_parent" => $_POST["parent"], "username" => $_SESSION["username"], "comment" => $_POST["comment"], "time" => time());
    echo showComment($arr);
}


?>