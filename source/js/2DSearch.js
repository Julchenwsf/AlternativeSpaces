var map, interestsInput, pageNum = 0;


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
        zoom: 1,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl:false,
        mapTypeControl:false,
        scaleControl:false,
        streetViewControl:false,
        overviewMapControl:false,
        rotateControl:false,
        styles: styles };
    map = new google.maps.Map(document.getElementById("sidebarSearchMap"), mapOptions);

    var markers = []

    var input = (document.getElementById('input-address-search'));

    var searchBox = new google.maps.places.SearchBox((input));

      google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
          return;
        }
        for (var i = 0, marker; marker = markers[i]; i++) {
          marker.setMap(null);
        }

        // For each place, get the icon, place name, and location.
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
          var image = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
          };

          // Create a marker for each place.
          var marker = new google.maps.Marker({
            map: map,
            icon: image,
            title: place.name,
            position: place.geometry.location
          });

          markers.push(marker);

          bounds.extend(place.geometry.location);
        }

        map.fitBounds(bounds);
      });

        google.maps.event.addListener(map, 'bounds_changed', function() {
          var bounds = map.getBounds();
          searchBox.setBounds(bounds);
        });


    google.maps.event.addListener(map, "dragend", function() {      //Listener for when the map is dragged, fires at the end of drag.
       doSearch(interestsInput.val(), map.getBounds());
    });

    google.maps.event.addListener(map, "zoom_changed", function() { //Listener for when the map changes zoom level.
       doSearch(interestsInput.val(), map.getBounds());
    });

    google.maps.event.addListenerOnce(map, 'idle', function(){      //Listener that fires when the map is first initialized.
        doSearch(interestsInput.val(), map.getBounds());
    });
}


//==== Search bar ====
$(document).ready(function() {
    interestsInput = $("#input-interest-search");
    initializeGMaps();                  //Initialize the map

    interestsInput.tokenInput("backend/db/DBInterests.php", {
        tokenLimit: 9,                  //Number of maximum simultaneous tags/interests
        resultsLimit: 10,               //Number of maximum auto-complete "suggestions"
        preventDuplicates: true,        //Ignore duplicate tags
        propertyToSearch: "interest_name",  //Property to search in the JS dict structure
        tokenValue: "interest_id",
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


$(document).on('click', '.contentBox', function(e){
    var id = $(this).attr("data-photo-id");
    window.history.pushState({"html": document.documentElement.innerHTML, "pageTitle": "Photo viewer"},"", 'photos.php?photo='+id);
    openPhotoOverlay(id);
});


//This fires every time the user scrolls
$(window).scroll(function() {
    //When users position at the page is within 100px from the end, get more pictures
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        doSearch(interestsInput.val(), map.getBounds(), true);
    }
});

function openPhotoOverlay(id) {
    $.get("backend/forms/photoenlarge.php", {photo_id: id}, function(data){modal.open({content: data, closeCallback:closePhotoOverlay});});
}

function closePhotoOverlay() {
    window.history.pushState({"html": document.documentElement.innerHTML, "pageTitle": "Photo viewer"},"", 'photos.php');
}

//Function to deal with AJAX search
function doSearch(tags, bounds, append) {
    if(!append) {   //Set append to true in order to append the result to already existing results. If false, the previous results are cleared
        pageNum = 0;
        $("#searchResults").html('');
    } else pageNum += 1;

    //Format the bounding box to be on format:
    //SW_lat SW_lng,NE_lat NE_lng
    var formattedBounds = toStringCoordinate(bounds.getSouthWest()) + "," + toStringCoordinate(bounds.getNorthEast());
    $.get("backend/db/DBPhotos.php?search=2D&boxloc=" + formattedBounds + "&interests=" + tags + "&page=" + pageNum, function(data) {
        $("#searchResults").append(data);
    });
}

//Function to format Google Maps API coordinate instance into a string
function toStringCoordinate(coordinate) {
    return coordinate.lat().toFixed(5) + " " + coordinate.lng().toFixed(5);
}