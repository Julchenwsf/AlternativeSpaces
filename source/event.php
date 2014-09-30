<?
include_once("backend/PageBuilder.php");

$eventHeader = <<<EOT
                   <div id="eventPage">
                        <div id="eventDetails">
                            <b>EventNameHere</b>
                        </div>
                        <div id="eventComments"></div>
                        <div id="eventMap"></div>
                   </div>
EOT;


$pb = new PageBuilder("Event");
$pb->appendContent($eventHeader);
echo $pb->toHTML();
?>