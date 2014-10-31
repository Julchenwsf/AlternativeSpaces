<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/functions/log.php");

$DBhost = "mysql.stud.ntnu.no";
$DBuser = "cdpgroup4_db";
$DBpass = "Kund3styrtProsj3kt";
$DBdatabase = "cdpgroup4_altspace";

$conn = mysql_connect($DBhost, $DBuser, $DBpass) or die  ('Error connecting to mysql');
mysql_select_db($DBdatabase);

?>