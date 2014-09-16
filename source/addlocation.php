<?
include_once("header.php");
//testfd
?>

<script type="text/javascript" src="js/data.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var map;
	var geocoder;
	var lastMarker;
	var lastInfo = new google.maps.InfoWindow();
	var values = '';

	for(var value in interests) {
	    values += '<option value="' + interests[value].name + '">' + interests[value].name + '</option>';
	}

	var iwform = 'Enter details:<br>'
	             + '<select name="interest" id="interest">' + values + '</select>'
                 + '<input name="data" id="data" /><br>'
                 + '<input type="button" value="Save" onclick="saveData()" />';

	function initialize() {
	    //http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
        var styles = [{
            "featureType": "poi",
            "elementType": "labels",
            "stylers": [{"visibility": "off"}]
        }];

		var mapOptions = {
            center: new google.maps.LatLng(59.92, 10.78),
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl:false,
            mapTypeControl:false,
            scaleControl:false,
            streetViewControl:false,
            overviewMapControl:false,
            rotateControl:false,
            styles: styles };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
		geocoder = new google.maps.Geocoder();

		google.maps.event.addListener(map, 'click', function(event) {
			lastInfo.close();
			var lat = event.latLng.lat();
			var lng = event.latLng.lng();
			var point = new google.maps.LatLng(lat, lng);
			createInputMarker(point);
		});

		for(var i in events) {
		    createMarker(events[i]);
		}
	}

	function createInputMarker(point) {
		var marker = new google.maps.Marker({position: point});
		var info = new google.maps.InfoWindow();
		info.setContent(iwform);
		info.setPosition(point);
		info.open(map,marker);
		lastMarker = marker;
		lastInfo = info;
	}

    function createMarker(markerData) {
        var iconBase = "img/interests/";
        var point = new google.maps.LatLng(markerData.lat, markerData.lng);
        var currentInterest = getInterest(markerData.interest);
        var marker = new google.maps.Marker({position: point, title:markerData.title, interest: markerData.interest, icon:iconBase + currentInterest["img"]})
        var info = new google.maps.InfoWindow();
        info.setContent(markerData["title"]);
        info.setPosition(point);

        google.maps.event.addListener(marker, 'click', function() {
            lastInfo.close();
            lastMarker=marker;
            lastInfo=info;
            info.open(map, marker); });
        marker.setMap(map);
    }

	function saveData() {
		var formDetails = escape(document.getElementById("data").value);
		var formInterests = document.getElementById("interest");
		var formSelectedInterest = formInterests.options[formInterests.selectedIndex].value;
		var latlng = lastMarker.getPosition();

		prompt("Copy to clipboard: Ctrl+C, Enter", '{"lat": ' + latlng.lat() + ', "lng": ' + latlng.lng() + ', "interest": "' + formSelectedInterest + '", "title": "' + formDetails + '", "time": ' + (1416470400 + 3600 * Math.floor((Math.random() * 12) + 1)) + '}');
		lastInfo.close();
	}


	function codeAddress() {
		var address = document.getElementById("searchaddress").value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if(status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				map.fitBounds(results[0].geometry.viewport);
				createInputMarker(results[0].geometry.location);
			} else {
				alert('Error: ' + status);
			}
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>



	<div style="text-align:center";font-weight:900;>Search: <input id="searchaddress" type="textbox" value=""><input type="button" value="Geocode" onclick="codeAddress()"></div>
	<div id="map" style="width: 100%; height: 100%"></div>


<?
include_once("footer.php");
?>