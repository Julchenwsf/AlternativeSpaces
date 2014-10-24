<html>
<head>
    <title>Select location</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />


    <link rel="stylesheet" type="text/css" href="../../styles/main.css">
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    <script type="text/javascript">
    var map, place;
    function initMap() {
        var styles = [
            {
                "featureType": "poi",
                "elementType": "labels",
                "stylers": [
                    {"visibility": "off"}
                ]
            }
        ];

        var mapOptions = {
            center: new google.maps.LatLng(0, 0),
            zoom: 2,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl: false,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            rotateControl: false,
            styles: styles };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        new google.maps.Marker({position: map.getCenter(), map: map, title: 'Center'}).bindTo('position', map, 'center');

        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('input-address-search'));
        autocomplete.bindTo('bounds', map);

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            place = autocomplete.getPlace();
            if (!place.geometry) return;

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
        });


        place = window.opener.place;
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            var loc = place.geometry.location;
            map.setCenter(new google.maps.LatLng(loc.lat(), loc.lng()));
            map.setZoom(17);  // Why 17? Because it looks good.
        }
    }

    function confirmSelection(sender) {
        try {
            window.opener.place = place;
            window.opener.mapSelectionCallback(map.getCenter());
        } catch (err) {}
        window.close();
        return false;
    }
    </script>

</head><body onload="initMap()">

<div id="main">
    <div id="mapBlock">
        <input type="text" id="input-address-search" placeholder="Address" />
        <div id="map" style="height:400px;width:600px;margin-bottom:5px"></div>
    </div>

    <div id="menuBlock">
        <button type="button" class="submitButton" onclick="return confirmSelection(this);" tabindex="1">Save location</button>
    </div>
</div>

</body></html>