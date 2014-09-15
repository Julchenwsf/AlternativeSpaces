var map, lastDetails;
var markerArray = [];

//==== GMaps ====
function initialize() {
    //Map stylers, see: http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
    //Disable labels for "points-of-interest" (Keep icons)
    var styles = [{
        "featureType": "poi",
        "elementType": "labels",
        "stylers": [{"visibility": "off"}]
    }];

    var mapOptions = {
        center: new google.maps.LatLng(59.92, 10.78),   //Center somewhere around Tøyen area
        zoom: 14,                                       //[0, 21]
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

//Function to add a marker to map
function createMarker(markerData) {
    var iconBase = "img/interests/";        //URL prefix for pin icons
    var point = new google.maps.LatLng(markerData.lat, markerData.lng); //Location of pin
    var currentInterest = getInterest(markerData.interest); //Get interest metadata
    var options = {position: point, title:markerData.title, interest: markerData.interest, icon:iconBase + currentInterest["img"], description: markerData.description, time: markerData.time};

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
    initialize();
    $("#input-interest-search").tokenInput(interests, {
        tokenLimit: 9,                  //Number of maximum simultaneous tags/interests
        resultsLimit: 10,               //Number of maximum auto-complete "suggestions"
        preventDuplicates: true,        //Ignore duplicate tags
        propertyToSearch: "name",       //Property to search in the JS dict structure
        resultsFormatter: function(item){   //Custom formatting for the auto-complete results
            return "<li><p class='teamname'><img src='img/interests/" + item.img + "' /> " + item.name + "</p><p class='score'>" + 2000 + "</p><div style='clear:both'></div></li>" },

        onAdd: function (item) {        //Is executed when user selects an option
            var relevantEvents = getEventsWithInterest(item.name);  //Get all events who's interest matches the search term
            for(var i=0; i<relevantEvents.length; i++) {            //For every event...
                createMarker(relevantEvents[i]);                    //Add it to the map
            }
        },

        onDelete: function (item) {     //Is executed when user removes a tag/token
            for(var mc = markerArray.length-1; mc>=0; mc--) {       //For all markers currently on the map...
                if(markerArray[mc].interest == item.name) {         //If markers interest matches the removed tag
                    markerArray[mc].setMap(null);                   //Remove it from the map
                    markerArray[mc].sidebarButton.remove();         //Remove if from the sidebar
                    markerArray.splice(mc, 1);                      //Remove it from the markers array
                }
            }
        },
        prePopulate: interests.slice(0, 3)                          //Pre-populate the search-bar with the 3 first interest-tags
    });
});


//==== Sidebar ====
//Adds a an element to the sidebar, takes in the GMaps marker and marker options dict.
//The basic HTML structure of the SidebarItem is
//<div class="eventDetails>
//  <div class="eventDetailsHeader>[Title]</div>
//  <div class="eventDetailsContent [hidden]">[Content]
//      <div class="time">[Time]</div>
function SidebarItem(marker, opts){
    var content = '<div class="eventDetailsContent hidden"><hr>' + opts.description + '<div class="time">' + timeConverter(opts.time) + '<div></div>';

    var header = document.createElement("div");
    header.className = "eventDetailsHeader";
    header.innerHTML = '<img src="' + opts.icon + '" alt="icon" /> ' + opts.title;

    var row = document.createElement("div");
    row.className = "eventDetails";
    row.innerHTML = header.outerHTML + content;

    //Clicking on the description should cause the same behaviour as clicking on the pin directly
    row.onclick = function(){
        google.maps.event.trigger(marker, 'click');
    }

    this.button = row;
    //Add the element under <div id="pinSidebar"...>
    document.getElementById("pinSidebar").appendChild(row);
}

//Removes an element from the sidebar
SidebarItem.prototype.remove = function(){
    this.button.remove();
}

//Function to "expand" the elements in the sidebar, works simply by toggling the class "hidden" (CSS: display:none; ) from the description.
SidebarItem.prototype.expand = function() {
    if(lastDetails) lastDetails.toggleClass("hidden");
    lastDetails = $(this.button).find(".eventDetailsContent");
    lastDetails.toggleClass("hidden");
}


//Function to convert unix timestamp (second since 1. Jan 1970) to DD. MMM YYYY at HH:MM format
function timeConverter(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return leadingZeroes(a.getDate(), 2) + '. ' + months[a.getMonth()] + ' ' + a.getFullYear() + ' at ' + leadingZeroes(a.getHours(), 2) + ':' + leadingZeroes(a.getMinutes(), 2);
 }


//Takes a variable and appends leading zeroes to it until the desired length is reached
function leadingZeroes(str, len) {
    str += '';
    while(str.length < len) str = "0" + str;
    return str;
}

