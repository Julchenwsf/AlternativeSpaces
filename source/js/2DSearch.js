var map, lastDetails, interestsInput, pageNum = 0;
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
        center: new google.maps.LatLng(0, 0),
        zoom: 0,
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

    google.maps.event.addListenerOnce(map, 'idle', function(){
        doSearch(interestsInput.val(), map.getBounds());
    });
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


$(document).on('click', '.photoBox', function(e){
    //Expand photobox
});


$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
    console.log($(window).scrollTop() + " " + $(window).height() + ": " + ($(window).scrollTop() + $(window).height()) + " | " + $(document).height());
       doSearch(interestsInput.val(), map.getBounds(), true);
    }
});


function doSearch(tags, bounds, append) {
    if(!append) {
        pageNum = 0;
        $("#searchResults").html('');
    } else pageNum += 1;

    var formattedBounds = toStringCoordinate(bounds.getSouthWest()) + "," + toStringCoordinate(bounds.getNorthEast());
    $.get("backend/db/DBPhotos?search=2D&boxloc=" + formattedBounds + "&interests=+" + tags + "&page=" + pageNum, function(data) {
        $("#searchResults").append(data);
    });
}

function toStringCoordinate(coordinate) {
    return coordinate.lat().toFixed(5) + " " + coordinate.lng().toFixed(5);
}