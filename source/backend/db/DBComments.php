<?
include_once("DBConnection.php");


function getComments() {
    $result = mysql_query("SELECT * FROM comments") or die(mysql_error());
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


function showComment($arr) {
    echo '<div class="commentBox com-'.$arr['comment_id'].'">
        <div class="comment">
        <div class="commentTime">'. commentTimeFormat($arr["time"]) .'</div>
        <div class="commentAvatar">
        <img src="img/design/defaultProfileIcon.png" width="30" height="30" alt="'.$arr['username'].'" />
        </div>

        <div class="commentText">
        <span class="commentName">'.$arr['username'].':</span> '.$arr['comment'].'
        </div>';

        if(isLoggedIn())
            echo '<div class="commentReply"><a href="" onclick="addComment(this,'.$arr['comment_id'].');return false;">Reply &raquo;</a></div>';

        echo'<div class="clear"></div>
    </div>';

    // Output the comment, and its replies, if any
    if($arr['children']) {
        foreach($arr['children'] as $r)
        showComment($r);
    }
    echo '</div>';
}


function commentTimeFormat($t){
    if(date('d')==date('d', $t)) return date('H:i', $t);
    return date('j. M Y \a\t H:i', $t);
}

?>