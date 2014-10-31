<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once("../db/DBEvents.php");
    if(!isLoggedIn()) {
        echo json_encode(array("success" => false, "response" => array("Must me logged in to create event")));
        return;
    }

    $time = strtotime($_POST["datetime"]);
    list($lat, $lng) = explode(' ', $_POST["location"]);
    $status = addEvent($_SESSION["username"], $_POST["title"], $_POST["description"], $_POST["interests"], $lat, $lng, $_POST["numPeople"], $time);
    $response = array("success" => is_numeric($status), "response" => $status);
    echo json_encode($response);


} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div class="submitTable">
        <div id="response"></div>
        <form id="newEventForm" action="backend/forms/eventform.php" method="post" onkeypress="return event.keyCode != 13;">
            <table>
                <tr>
                    <th colspan="2" id="center">Event Creation</th>
                </tr>
                <tr>
                    <td colspan="2"><input type="text" name="title" placeholder="Title" /></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="button" onClick='openMapPicker()' class="submitButton right">Map</button>
                    <div style="width:360px;float:left"><input type="text" name="locationDisplay" placeholder="Location" id="location" /></div>
                    <input type="hidden" name="location" id="locationHidden"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="text" id="eventInterests" name="interests" /></td>
                </tr>
                <tr>
                    <td><input type="text" id="datetimepicker" name="datetime" placeholder="Date & time" /></td>
                    <td><input type="text" name="numPeople" placeholder="No of people invited" /></td>
                </tr>
                <tr>
                    <td colspan="2" id="center"><textarea rows="4" cols="55" name="description" placeholder="Description"></textarea></td>

                </tr>
                <tr>
                  <td colspan="2" id="center"><button class="submitButton" name="submit" type="submit">Create Event</button></td>
               </tr>
           </table>
        </form>
    </div>
    <script type="text/javascript">
    $('#newEventForm').submit(function (ev) {
        $.ajax({
            type: "POST",
            url: "/backend/forms/eventform.php",
            data: $("#newEventForm").serialize(),
            dataType: "JSON",
            success: function(data) {
                if(data["success"]) {
                   window.location.replace("/map/events/" + data["response"]);
                } else {
                   var out = "";
                   for(var error in data["response"]) {
                       out += "<li>" + data["response"][error] + "</li>";
                   }
                   $("#response").html('<ul class="error">' + out + "</ul>");
                }
            }
        });
        ev.preventDefault();
    });

    jQuery('#datetimepicker').datetimepicker({
        format:'d.m.Y H:i',
        minDate: 0,
        maxData: "1Y",
        startDate: new Date()
    });

    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'), { types: ['geocode'] });
    var place;
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        place = autocomplete.getPlace();
        if (!place.geometry) return;

        $("#locationHidden").val(place.geometry.location.lat() + " " + place.geometry.location.lng());
    });


    function openMapPicker() {
        window.open("/backend/forms/maplookup.php",
            "Select location", "width=620,height=460,location=no,menubar=no,resizable=no,status=no,toolbar=no");
    }

    function mapSelectionCallback(result) {
        $("#locationHidden").val(result.lat() + " " + result.lng());

        new google.maps.Geocoder().geocode({'latLng': new google.maps.LatLng(result.lat(), result.lng())}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK && results[0]) {
                $("#location").val(results[0].formatted_address);
            } else {
                $("#location").val("Selected from map");
            }
        });
	}

    $(document).ready(function() {
        interestsInput = $("#eventInterests");

        interestsInput.tokenInput("/backend/db/DBInterests.php", {
            tokenLimit: 4,                  //Number of maximum simultaneous tags/interests
            resultsLimit: 10,               //Number of maximum auto-complete "suggestions"
            preventDuplicates: true,        //Ignore duplicate tags
            searchingHint: "Interests",
            propertyToSearch: "interest_name",  //Property to search in the JS dict structure
            tokenValue: "interest_id",
            resultsFormatter: function(item){   //Custom formatting for the auto-complete results
                return "<li><p class='interest_name'><img src='/img/interests/" + item.interest_icon + "' /> " + item.interest_name + "</p><div style='clear:both'></div></li>" }
        });
    });
    </script>
EOT;
} ?>