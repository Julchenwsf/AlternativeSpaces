var map, lastDetails, interestsInput;
var markerArray = [];


//==== GMaps ====
function initializeGMaps() {
    //Map stylers, see: http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
    //Disable labels for "points-of-interest" (Keep icons)
    var styles = [{
        "featureType": "poi",
        "elementType": "labels",
        "stylers": [{"visibility": "off"}]
    }];

    var mapOptions = {
        center: new google.maps.LatLng(0, 0),   //Center somewhere around Tøyen area
        zoom: 0,                                       //[0, 21]
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl:false,
        mapTypeControl:false,
        scaleControl:false,
        streetViewControl:false,
        overviewMapControl:false,
        rotateControl:false,
        styles: styles };
    map = new google.maps.Map(document.getElementById("sidebarSearchMap"), mapOptions);

    google.maps.event.addListener(map, "dragend", function() {
       doSearch(interestsInput.val(), map.getBounds());
    });

    google.maps.event.addListener(map, "zoom_changed", function() {
       doSearch(interestsInput.val(), map.getBounds());
    });
}

//Function to add a marker to map
function createMarker(markerData, currentInterest) {
    var iconBase = "img/interests/";        //URL prefix for pin icons
    var point = new google.maps.LatLng(markerData.lat, markerData.lng); //Location of pin
    var options = {position: point, title:markerData.title, interest: markerData.interest, icon:iconBase + currentInterest["interest_icon"], description: markerData.description, time: markerData.time};

    var pushPin = new google.maps.Marker({map: map});
    pushPin.setOptions(options);

    //When user clicks the pin...
    google.maps.event.addListener(pushPin, "click", function(){
        if(this.sidebarButton) this.sidebarButton.expand();     //Expand the sidebar details for current pin
    });

    pushPin.sidebarButton = new SidebarItem(pushPin, options);  //Create new SidebarButton for current pin and save reference for click event handling
    markerArray.push(pushPin);                                  //Add current pin to array of pins
}


//==== Search bar ====
$(document).ready(function() {
    interestsInput = $("#input-interest-search");
    initializeGMaps();
    interestsInput.tokenInput("backend/db/DBInterests.php", {
        tokenLimit: 9,                  //Number of maximum simultaneous tags/interests
        resultsLimit: 10,               //Number of maximum auto-complete "suggestions"
        preventDuplicates: true,        //Ignore duplicate tags
        propertyToSearch: "interest_name",  //Property to search in the JS dict structure
        tokenValue: "interest_id",
        tokenDelimiter: " +",
        resultsFormatter: function(item){   //Custom formatting for the auto-complete results
            return "<li><p class='interest_name'><img src='img/interests/" + item.interest_icon + "' /> " + item.interest_name + "</p><p class='score'>" + 2000 + "</p><div style='clear:both'></div></li>" },

        onAdd: function (item) {        //Is executed when user selects an option
            doSearch(interestsInput.val(), map.getBounds());
        },

        onDelete: function (item) {     //Is executed when user removes a tag/token
            doSearch(interestsInput.val(), map.getBounds());
        }
        //prePopulate: interests.slice(0, 3)                          //Pre-populate the search-bar with the 3 first interest-tags
    });
});



function doSearch(tags, bounds) {
    console.log("Ran! " + tags + " | " + bounds);
    var formattedBounds = toStringCoordinate(bounds.getSouthWest()) + "," + toStringCoordinate(bounds.getNorthEast());
    $.get("backend/db/DBPhotos?search=2D&boxloc=" + formattedBounds + "&interests=" + tags, function(data) {
        $("#searchResults").html(data);
    });
}

function toStringCoordinate(coordinate) {
    return coordinate.lat().toFixed(5) + " " + coordinate.lng().toFixed(5);
}