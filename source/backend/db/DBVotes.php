<?php
include('DBConnection.php');

function registerVote($cid) {
    if(!isLoggedIn()) return;
    mysql_query("INSERT INTO votes (username, content_id) VALUES ('$_SESSION[username]', '$cid')");
}

function didVote($cid) {
    if(!isLoggedIn()) false;
    $result = mysql_query("SELECT EXISTS(SELECT 1 FROM votes WHERE username='$_SESSION[username]' AND content_id='$cid' LIMIT 1)");
    return mysql_result($result, 0) == 1;
}
