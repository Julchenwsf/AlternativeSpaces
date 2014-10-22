<?
include_once("backend/PageBuilder.php");

$content = 'Hi';

$pb = new PageBuilder("Index");
$pb->appendContent($content);

echo $pb->toHTML();
?>