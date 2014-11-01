<?
date_default_timezone_set("Europe/Oslo");
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/functions/log.php");

$DBhost = "localhost";
$DBuser = "mysplotc_web";
$DBpass = "Kund3styrtProsj3kt";
$DBdatabase = "mysplotc_altspace";

$conn = mysql_connect($DBhost, $DBuser, $DBpass) or die  ('Error connecting to mysql');
mysql_select_db($DBdatabase);

?>