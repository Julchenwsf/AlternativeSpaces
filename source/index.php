<?
$title = "Index";
include_once("header.php");
?>

<link rel="stylesheet" href="styles/token-input.css" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/data.js"></script>
<script type="text/javascript" src="js/jquery.tokeninput.js"></script>
<script type="text/javascript">
	var map;
	var geocoder;
	var lastMarker;
	var lastInfo = new google.maps.InfoWindow();
	var markers = [];

	function initialize() {
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
		markers.push(marker);
	}

	function codeAddress() {
		var address = document.getElementById("searchaddress").value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if(status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				map.fitBounds(results[0].geometry.viewport);
			} else {
				alert('Error: ' + status);
			}
		});
	}


	$(document).ready(function() {
	    initialize();
    	$("#input-interest-search").tokenInput(interests, {
    		tokenLimit: 9,
    		resultsLimit: 10,
    		preventDuplicates: true,
    		propertyToSearch: "name",
    		resultsFormatter: function(item){
    			return "<li><p class='teamname'><img src='img/interests/" + item.img + "' /> " + item.name + "</p><p class='score'>" + 2000 + "</p><div style='clear:both'></div></li>" },

    		onAdd: function (item) {
                var relevantEvents = getEventsWithInterest(item.name);
                for(var i=0; i<relevantEvents.length; i++) {
                    createMarker(relevantEvents[i]);
                }
    		},

    		onDelete: function (item) {
                for(var mc = markers.length-1; mc>=0; mc--) {
                    if(markers[mc].interest == item.name) {
                        markers[mc].setMap(null);
                        markers.splice(mc, 1);
                    }
                }
    		},
    		prePopulate: interests.slice(0, 3)
    	});
    });
</script>
    <div class="input-search-area">
        <div id="input-interest-search"></div>
    </div>
	<div id="map" style="width:100%; height:600px"></div>


<?
include_once("footer.php");
?>