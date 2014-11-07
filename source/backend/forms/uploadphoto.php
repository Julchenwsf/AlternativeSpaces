<?
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/db/DBPhotos.php");
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/functions/image.php");
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/functions/log.php");


if(isset($_FILES['image'])) {
    if(!isLoggedIn() && isset($_POST["username"]) && isset($_POST["password"])) login($_POST["username"], $_POST["password"]);
    $coordinates = strlen($_POST["location"]) > 1 ? explode(" ", $_POST["location"]) : false;

    $status = uploadImage($_POST["title"], $_POST["interests"], $_POST["description"], $_FILES["image"], $coordinates);
    $response = array("success" => is_numeric($status), "response" => $status);
    echo json_encode($response);
}


function uploadImage($title, $interests, $description, $image, $coordinates) {
    $errors = array();
    if(!isLoggedIn()) $errors[] = "You must be logged in to upload!";

    $fileErrors = array(null, "The file is to big", "The file is to big", "Only part of the file was uploaded", "No file was uploaded", null,
        "Missing a temporary folder", "Failed to write file to disk", "File upload stopped by extension");
    $uploaddir = '/home1/mysplotc/public_html/images/';
    $valid_exts = array('jpeg', 'jpg');
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, $valid_exts)) $errors[] = "Invalid file type, only .jpeg and .jpg";
    if($image['error'] != 0) $errors[] = $fileErrors[$image["error"]];

    if(!$coordinates) $coordinates = getEXIFGPS($image["tmp_name"]);
    if(!$coordinates) $errors[] = "Image has no coordinates, turn on your GPS";
    list($lat, $lng) = $coordinates;

    if(!empty($errors)) return $errors;
    $photoID = addPhoto($_SESSION["username"], $title, $lat, $lng, $interests, $description);
    if(!is_numeric($photoID)) return $photoID;

    if(!(scaleImage($image, 1024, 1024, $uploaddir . "large/" . $photoID . "." . $ext) and scaleImage($image, 240, 180, $uploaddir . "thumb/" . $photoID . "." . $ext))) {
        removePhoto($photoID);
        $errors[] = "Something went wrong! :(";
    }
    return (empty($errors)) ? $photoID : $errors;
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div class="submitTable">
        <div id="response"></div>
        <form id="newPhotoForm" enctype="multipart/form-data" action="javascript:;" method="post">
            <table>
                <tr>
                    <th colspan="2" id="center">Upload photo</th>
                </tr>
                <tr>
                    <td colspan="2"><input name="title" type="text" placeholder="Title" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="text" id="photoInterests" name="interests" /></td>
                </tr>
                <tr>
                    <td colspan="2" id="center"><textarea rows="4" cols="55" name="description" placeholder="Description"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><input name="image" id="imageFile" type="file" /></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="button" onClick='openMapPicker()' class="submitButton right" id="locationButton" disabled>Map</button>
                    <div style="width:360px;float:left"><input type="text" name="locationDisplay" placeholder="Location" id="location" disabled /></div>
                    <input type="hidden" name="location" id="locationHidden"></td>
                </tr>
                <tr>
                  <td colspan="2" id="center"><button class="submitButton" name="submit" type="submit">Upload Photo</button></td>
               </tr>
           </table>
        </form>
    </div>

    <script type="text/javascript">
    $('#newPhotoForm').submit(function (ev) {
        $.ajax({
            type: "POST",
            url: "/backend/forms/uploadphoto.php",
            data: new FormData( this ),
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data) {
                if(data["success"]) {
                   window.location.replace("/map/photos/" + data["response"]);
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

    $('#imageFile').on("change", function(){
        var inputFile = $(this).prop("files")[0];
        var formData = new FormData();
        formData.append("image", inputFile);
        $.ajax({
            type: "POST",
            url: "/backend/forms/getlocation.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data) {
                if(data["success"]) {
                    setLocation(data["response"][0], data["response"][1]);
                    $("#locationHidden").val(data["response"][0] + " " + data["response"][1]);
                    $("#locationButton").prop("disabled", true);
                } else {
                    $("#locationButton").prop("disabled", false);
                    $("#location").val("");
                    $("#locationHidden").val("");
                }
            }
        });
    });

    function openMapPicker() {
        window.open("/backend/forms/maplookup.php",
            "Select location", "width=620,height=460,location=no,menubar=no,resizable=no,status=no,toolbar=no");
    }

    function mapSelectionCallback(result) {
        $("#locationHidden").val(result.lat() + " " + result.lng());
        setLocation(result.lat(), result.lng());
	}

	function setLocation(lat, lng) {
	    new google.maps.Geocoder().geocode({'latLng': new google.maps.LatLng(lat, lng)}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK && results[0]) {
                $("#location").val(results[0].formatted_address);
            } else {
                $("#location").val("Lat: " + lat + ", Lng: " + lng);
            }
        });
    }

    $(document).ready(function() {
        interestsInput = $("#photoInterests");

        interestsInput.tokenInput("/backend/db/DBInterests.php", {
            tokenLimit: 4,                  //Number of maximum simultaneous tags/interests
            resultsLimit: 10,               //Number of maximum auto-complete "suggestions"
            preventDuplicates: true,        //Ignore duplicate tags
            searchingHint: "Interests",
            propertyToSearch: "interest_name",  //Property to search in the JS dict structure
            tokenValue: "interest_id",
            resultsFormatter: function(item){   //Custom formatting for the auto-complete results
                return "<li><p class='interest_name'><img src='/img/interests/" + item.interest_icon + "' /> " + item.interest_name + "</p><div style='clear:both'></div></li>" },
            tokenFormatter: function(item){   //Custom formatting for the auto-complete results
                return "<li><img src='/img/interests/" + item.interest_icon + "' title='" + item.interest_name + "' /><p> " + item.interest_name + "</p></li>" }

        });
    });
    </script>
EOT;
}

?>