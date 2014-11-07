<?
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/db/DBComments.php");

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


function showComment($arr) {
    $temp = '<div class="commentBox com-'.$arr['comment_id'].'">
        <div class="comment">
        <div class="commentTime">'. unixTimeToStringDate($arr["time"]) .'</div>
        <div class="commentAvatar">
        <img src="/img/design/defaultProfileIcon.png" width="30" height="30" alt="'.$arr['username'].'" />
        </div>

        <div class="commentText">
        <span class="commentName">'.$arr['username'].':</span> '.$arr['comment'].'
        </div>';

    if(isLoggedIn())
        $temp .= '<div class="commentReply"><a href="" onclick="addComment(this,'.$arr['comment_id'].');return false;">Reply &raquo;</a></div>';

    $temp .= '<div class="clear"></div>
    </div>';

    // Output the comment, and its replies, if any
    if(array_key_exists("children", $arr)) {
        foreach($arr['children'] as $r)
            $temp .= showComment($r);
    }
    $temp .= '</div>';
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