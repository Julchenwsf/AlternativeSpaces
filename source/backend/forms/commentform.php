<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/db/DBComments.php");

function getCommentsForm($id) {
    $temp = '<div id="commentArea" data-thread-id="' . $id . '">';

    $comments = getComments($id);
    foreach($comments as $c) {
        $temp .= showComment($c);
    }

    $temp .= '</div>';
    if(isLoggedIn())
        $temp .= '<button type="button" class="submitButton" onclick="addComment()" >Add a comment</button>';
    return $temp;
}


if(isset($_POST["comment"])) {
    if(!isLoggedIn()) return;
    $status = insertComment($_POST["thread"], $_POST["parent"], $_SESSION["username"], $_POST["comment"]);
    if(!$status) return;
    $arr = array("comment_id" => $status, "comment_parent" => $_POST["parent"], "username" => $_SESSION["username"], "comment" => $_POST["comment"], "time" => time());
    echo showComment($arr);
}


?>