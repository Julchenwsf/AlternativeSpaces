<?
include_once("backend/PageBuilder.php");
include_once("backend/forms/regform.php");

$pb = new PageBuilder("Register");


$pb->appendContent(getForm());
echo $pb->toHTML();


?>
