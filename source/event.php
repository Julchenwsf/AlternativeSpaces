<?
include_once("backend/PageBuilder.php");

$eventHeader = <<<EOT
                   <div id="eventPage">
                        <div id="eventDetails"></div>
                        <div id="eventMap2"></div>
                        <div id="eventComments"></div>
                   </div>
EOT;


$pb = new PageBuilder("Event");
$pb->appendContent($eventHeader);
echo $pb->toHTML();
?>