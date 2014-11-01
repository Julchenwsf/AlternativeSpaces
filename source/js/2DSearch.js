var map, interestsInput, database, markerCluster, markers, pageNum = 0;
var ltags, lbounds;

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
    markerCluster = new MarkerClusterer(map, []);

    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('input-address-search'));
    autocomplete.bindTo('bounds', map);

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) return;

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
        }
        doSearch();
    });

    google.maps.event.addListener(map, 'idle', function(){      //Listener that fires when the map is first initialized.
        doSearch();
    });
}


//==== Search bar ====
$(document).ready(function() {
    database = $('input[name="database"]:checked').val();
    interestsInput = $("#input-interest-search");
    initializeGMaps();                  //Initialize the map

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
            return "<li><img src='/img/interests/" + item.interest_icon + "' title='" + item.interest_name + "' /><p> " + item.interest_name + "</p></li>" },

        onAdd: function (item) {        //Is executed when user selects an option
            doSearch();
        },

        onDelete: function (item) {     //Is executed when user removes a tag/token
            doSearch();
        }
        //prePopulate: interests.slice(0, 3)                          //Pre-populate the search-bar with the 3 first interest-tags
    });


    $("input[name=database]:radio").change(function () {
        database = $('input[name="database"]:checked').val();
        closeOverlay();
        doSearch();
    });

});


$(document).on('click', '.contentClickArea', function(e){
    var id = $(this).attr("data-content-id");
    window.history.pushState({"html": document.documentElement.innerHTML, "pageTitle": "Viewer"},"", 'index.php?type=' + database + '&id='+id);
    openOverlay(id);
});


$(document).on('mouseenter', '.contentBoxDescription .token-input-token', function(e){
    $(this).find("p").toggleClass("hidden");
});

$(document).on('mouseleave', '.contentBoxDescription .token-input-token', function(e){
    $(this).find("p").toggleClass("hidden");
});



//This fires every time the user scrolls
$(window).scroll(function() {
    //When users position at the page is within 100px from the end, get more pictures
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        doSearch(true);
    }
});

function openOverlay(id) {
    window.history.pushState({"html": document.documentElement.innerHTML, "pageTitle": "Viewer"},"", 'index.php?type=' + database + '&id='+id);
    $.get("/backend/forms/overlay" + database + ".php", {id: id}, function(data){modal.open({content: data, closeCallback:closeOverlay});});
}

function closeOverlay() {
    window.history.pushState({"html": document.documentElement.innerHTML, "pageTitle": ""},"", '/map/' + database);
}

//Function to deal with AJAX search
function doSearch(append) {
    console.log(map.getBounds() + interestsInput.val());
    if(lbounds == map.getBounds() && ltags == interestsInput.val() && ! append) return;
    ltags = interestsInput.val();
    lbounds = map.getBounds();

    if(!append) {   //Set append to true in order to append the result to already existing results. If false, the previous results are cleared
        pageNum = 0;
        markers = [];
        markerCluster.clearMarkers();
        $("#searchResults").html('');
    } else pageNum += 1;

    //Format the bounding box to be on format:
    //SW_lat SW_lng,NE_lat NE_lng
    var formattedBounds = toStringCoordinate(lbounds.getSouthWest()) + "," + toStringCoordinate(lbounds.getNorthEast());
    $.ajax({
        type: "GET",
        url: "backend/db/DB" + capitalize(database) + ".php?search=2D&boxloc=" + formattedBounds + "&interests=" + ltags + "&page=" + pageNum,
        dataType: "JSON",
        success: function (data) {
            $("#searchResults").append(data["response"]);

            for(key in data["locations"]) {
                var marker = new google.maps.Marker({position: new google.maps.LatLng(data["locations"][key][1], data["locations"][key][2])});
                google.maps.event.addListener(marker, 'click', function() {
                    openOverlay(data["locations"][key][0]);
                });

                markers.push(marker);
            }
            markerCluster = new MarkerClusterer(map, markers);
        }
    });
}

function vote(way, cid, db) {
    $.ajax({
        type: "GET",
        url: "/backend/forms/voteform.php?vote=" + way + "&cid=" + cid + "&db=" + db,
        dataType: "JSON",
        success: function (data) {
            if (data["success"]) {
                $("div[data-voter-id='" + cid + "']").replaceWith(data["response"]);
            } else {
                alert("Something went wrong :(");
            }
        }
    });
}

//Function to format Google Maps API coordinate instance into a string
function toStringCoordinate(coordinate) {
    return coordinate.lat().toFixed(5) + " " + coordinate.lng().toFixed(5);
}

function capitalize(s) {
    return s && s[0].toUpperCase() + s.slice(1);
}