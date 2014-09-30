<?
//This class connects to our MySQL database at NTNU, include this file at the top of every PHP file where you use the database using:
//include_once("DBConnection.php");

include_once("../functions/log.php");

$DBhost = "mysql.stud.ntnu.no";
$DBuser = "cdpgroup4_db";
$DBpass = "Kund3styrtProsj3kt";
$DBdatabase = "cdpgroup4_altspace";

$conn = mysql_connect($DBhost, $DBuser, $DBpass) or die  ('Error connecting to mysql');
mysql_select_db($DBdatabase);

?>