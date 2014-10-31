<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";

if (isset($_GET["id"])) {
    include_once($path . "backend/PageBuilder.php");
    include_once($path . "backend/forms/commentform.php");
    include_once($path . "backend/db/DBEvents.php");
    include_once($path . "backend/functions/sharing.php");

    $eventData = fetchEvent($_GET["id"]);
    $comments = getCommentsForm("e" . $_GET["id"]);
    $sharing = getShareButtons("http://folk.ntnu.no/valerijf/div/AlternativeSpaces/source/index.php?type=events&id=" . $_GET["id"], $eventData["event_name"], $eventData["description"]);


    $eventTime = eventTimeFormat($eventData['event_time']);
    $eventTitle = $eventData["event_name"];
    $eventDesc = $eventData['description'];
    $lat = $eventData["latitude"];
    $lng = $eventData["longitude"];
    $numPeople = $eventData["no_of_people"];
    $creator = $eventData["event_creator"];
    $interests = explode(" ", $eventData["interests"]);
    $interestIcons="";
     foreach($interests as &$interest){
            $data = getInterest($interest);
            $interestIcons .= '<li class="token-input-token"><img src="img/interests/' .$data["interest_icon"] .'"/><p>' .$data["interest_name"] .'</p></li>';

     }

    echo <<<EOT
    <div id="eventPage">
        <div class="eventLeft">
            <div class="eventImage">
                <img src="http://maps.googleapis.com/maps/api/staticmap?center=$lat,$lng&zoom=13&size=175x175&maptype=roadmap&markers=color:red%7Clabel:A%7C$lat,$lng" />
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
                <ul class="token-input-list"> $interestIcons</ul>
            </div>

            <div class="eventDescription center">
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
                $sharing
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
    <div style="clear: both;"></div>
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

}
?>