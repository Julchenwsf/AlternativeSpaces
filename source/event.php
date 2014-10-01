<?

include_once("backend/PageBuilder.php");
include_once("backend/forms/commentform.php");
$comments = getCommentsForm(4);

$eventHeader = <<<EOT
<div id="eventPage">
    <div class="eventLeft">
        <div class="eventImage">
            <a href="index.php"><img src="img/design/football.png" /></a>
        </div>
        <div class="eventDescription">
            <div class="eventDescriptionHeader">
                <a class="titleText">Description</a>
            </div>
            <fieldset>
                <legend>When</legend>
                15. October at 13:00
            </fieldset>
            <fieldset>
                <legend>Where</legend>
                Tøyenparken
            </fieldset>
            <fieldset>
                <legend>What</legend>
                Play football
            </fieldset>
        </div>

    </div>
    <div class="eventRight">
        <div class="eventImage">
            <img src="http://maps.googleapis.com/maps/api/staticmap?center=59.9162809,10.7775311&zoom=13&size=175x175&maptype=roadmap&markers=color:red%7Clabel:C%7C40.718217,-73.998284" />
        </div>
        <div class="eventDescription">
            <div class="eventDescriptionHeader">
                <a class="titleText">Join the event</a>
            </div>
        <fieldset>
            <legend>Event status</legend>
            10/20
        </fieldset>
        <button type="button" id="attendButton" class="submitButton">Attend</button>
        </div>
    </div>

   <div class="eventMiddle">
        <div class="eventContent">
            <h2>Come play football</h2>
            <hr/>
            Here is some description. Here is some description. Here is some description. Here is some description. Here is some description. Here is some description.
        </div>
    </div>

   <div class="eventMiddle">
        <div class="eventContent">
            $comments
        </div>
   </div>
</div>
EOT;


$pb = new PageBuilder("Event");
$pb->addCSSImport("styles/event.css");
$pb->addCSSImport("styles/comments.css");
$pb->addJSImport("js/comment.js");
$pb->appendContent($eventHeader);
echo $pb->toHTML();
?>