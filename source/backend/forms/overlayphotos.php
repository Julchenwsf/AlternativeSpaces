<?
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/forms/commentform.php");
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/db/DBPhotos.php");
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/functions/sharing.php");
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/db/DBInterests.php");

function photoTimeFormat($t){
    if(date('d')==date('d', $t)) return "Today at " . date('H:i', $t);
    return date('j. M y \a\t H:i', $t);
}

if(isset($_GET["id"])) {
    $photoData = getPhotoDetails($_GET["id"]);
    $interests = explode(" ", $photoData["interests"]);
    $interestIcons = "";
    foreach($interests as &$interest){
        $data = getInterest($interest);
        $interestIcons .= '<li class="token-input-token"><img src="img/interests/' .$data["interest_icon"] .'"/><p>' .$data["interest_name"] . '</p></li>';
    }

    $photoTime = photoTimeFormat($photoData['upload_time']);
    $photoTitle = $photoData["photo_title"];
    $photoDesc = $photoData['description'];
    $creator = $photoData["photo_uploader"];
    $lat = $photoData["latitude"];
    $lng = $photoData["longitude"];
    $sharing = getShareButtons("", $photoTitle, $photoDesc); //TODO: Update URL
    $comments = getCommentsForm("p" . $_GET["id"]);

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
                    $photoTime
                </fieldset>
                <fieldset id="addressLocation">
                    <legend>Where</legend>
                </fieldset>
                <fieldset>
                    <legend>Who</legend>
                    $creator
                </fieldset>
                <ul class="token-input-list">$interestIcons</ul>
            </div>
        </div>

       <div class="eventMiddle">
       <div class="largeContentBox"><div id="enlargedPhoto"><img src="/images/large/$_GET[id].jpg" /></div></div>
            <div class="eventContent">
                <h2>$photoTitle</h2>
                <hr/>
                $photoDesc
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