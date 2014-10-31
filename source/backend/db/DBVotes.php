<?php
include('DBConnection.php');

function registerVote($cid, $vote) {
    if(!isLoggedIn()) return;
    mysql_query("INSERT INTO votes (username, content_id, vote) VALUES ('$_SESSION[username]', '$cid', '$vote')
    ON DUPLICATE KEY UPDATE vote='$vote'");
}

function didVote($cid) {
    if(!isLoggedIn()) return 0;
    $result = mysql_query("SELECT vote FROM votes WHERE username='$_SESSION[username]' AND content_id='$cid' LIMIT 1");
    $row = mysql_fetch_assoc($result);
    return $row ? (($row["vote"] == 1) ? 1 : -1) : 0;
}
