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
    $comment = filter_var(mysql_real_escape_string($comment), FILTER_SANITIZE_SPECIAL_CHARS);
    if(!is_numeric($parent)) return false;
    mysql_query("INSERT INTO comments (thread_id, comment_parent, username, comment, time) VALUES ('$threadID', '$parent', '$username', '$comment', '" . time() ."')") or die(mysql_error());
    return mysql_insert_id();
}

?>