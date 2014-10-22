<?

if (isset($_GET["id"])) {
    include_once("backend/PageBuilder.php");
    include_once("backend/forms/commentform.php");
    include_once("backend/db/DBEvents.php");

    $comments = getCommentsForm("e" . $_GET["id"]);
    $eventData = fetchEvent($_GET["id"]);

    $eventTime = eventTimeFormat($eventData['event_time']);
    $eventTitle = $eventData["event_name"];
    $eventDesc = $eventData['description'];
    $lat = $eventData["latitude"];
    $lng = $eventData["longitude"];
    $numPeople = $eventData["no_of_people"];
    $creator = $eventData["event_creator"];

    $eventHeader = <<<EOT
    <div id="eventPage">
        <div class="eventLeft">
            <div class="eventImage">
                <a href="index.php"><img src="img/design/football.png" /></a>
            </div>
            <div class="eventDescription">
                <div class="eventDescriptionHeader">
                    <span class="titleText">Description</span>
                </div>
                <fieldset>
                    <legend>When</legend>
                    $eventTime
                </fieldset>
                <fieldset id="addressLocation">
                    <legend>Where</legend>
                </fieldset>
                <fieldset>
                    <legend>Who</legend>
                    $creator
                </fieldset>
            </div>

        </div>
        <div class="eventRight">
            <div class="eventImage">
                <img src="http://maps.googleapis.com/maps/api/staticmap?center=$lat,$lng&zoom=13&size=175x175&maptype=roadmap&markers=color:red%7Clabel:A%7C$lat,$lng" />
            </div>
            <div class="eventDescription">
                <div class="eventDescriptionHeader">
                    <span class="titleText">Join the event</span>
                </div>
            <fieldset>
                <legend>Event status</legend>
                0/$numPeople
            </fieldset>
            <button type="button" id="attendButton" class="submitButton">Attend</button>
            </div>
        </div>

       <div class="eventMiddle">
            <div class="eventContent">
                <h2>$eventTitle</h2>
                <hr/>
                $eventDesc
            </div>
        </div>

       <div class="eventMiddle">
            <div class="eventContent">
                <h2>Comments</h2>
                <hr/>
                $comments
            </div>
       </div>
    </div>
    <script type="text/javascript">
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng($lat, $lng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK && results[0]) {
                $("#addressLocation").append(results[0].formatted_address);
            } else {
                $("#addressLocation").append("Unknown");
            }
        });
    </script>
EOT;


    $pb = new PageBuilder("Event");
    $pb->addCSSImport("styles/event.css");
    $pb->addCSSImport("styles/comments.css");
    $pb->addJSImport("js/comment.js");
    $pb->appendContent($eventHeader);
    echo $pb->toHTML();
}
?>