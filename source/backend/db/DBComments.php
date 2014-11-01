<?
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/db/DBConnection.php");


function getComments($threadID) {
    $threadID = mysql_real_escape_string($threadID);
    $result = mysql_query("SELECT comment_id, comment_parent, username, comment, time FROM comments WHERE thread_id='$threadID'") or die(mysql_error());
    $comments = array();
    while($row = mysql_fetch_assoc($result)) {
        $row['children'] = array();
        $comments[$row['comment_id']] = $row;
    }

    foreach ($comments as $k => &$v) {
        if ($v['comment_parent'] != 0) {
            $comments[$v['comment_parent']]['children'][] =& $v;
        }
    }
    unset($v);

    // delete the childs comments from the top level
    foreach ($comments as $k => $v) {
        if ($v['comment_parent'] != 0) {
            unset($comments[$k]);
        }
    }

    return $comments;
}


function insertComment($threadID, $parent, $username, $comment) {
    $threadID = mysql_real_escape_string($threadID);
    $comment = mysql_real_escape_string($comment);
    if(!is_numeric($parent)) return false;
    mysql_query("INSERT INTO comments (thread_id, comment_parent, username, comment, time) VALUES ('$threadID', '$parent', '$username', '$comment', '" . time() ."')") or die(mysql_error());
    return mysql_insert_id();
}


function showComment($arr) {
    $temp = '<div class="commentBox com-'.$arr['comment_id'].'">
        <div class="comment">
        <div class="commentTime">'. commentTimeFormat($arr["time"]) .'</div>
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


function commentTimeFormat($t){
    if(date('d')==date('d', $t)) return date('H:i', $t);
    return date('j. M Y \a\t H:i', $t);
}

?>