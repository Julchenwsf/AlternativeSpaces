var map;
var markerArray = [];

function initialize() {
    var styles = [{
        "featureType": "poi",
        "elementType": "labels",
        "stylers": [{"visibility": "off"}]
    }];

    var mapOptions = {
        center: new google.maps.LatLng(59.92, 10.78),
        zoom: 14,
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
    var options = {position: point, title:markerData.title, interest: markerData.interest, icon:iconBase + currentInterest["img"]};

    var pushPin = new google.maps.Marker({map: map});
    pushPin.setOptions(options);

    google.maps.event.addListener(pushPin, "click", function(){
        if(this.sidebarButton) this.sidebarButton.expand();
    });

    pushPin.sidebarButton = new SidebarItem(pushPin, options);
    markerArray.push(pushPin);
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
            for(var mc = markerArray.length-1; mc>=0; mc--) {
                if(markerArray[mc].interest == item.name) {
                    markerArray[mc].setMap(null);
                    markerArray[mc].sidebarButton.remove();
                    markerArray.splice(mc, 1);
                }
            }
        },
        prePopulate: interests.slice(0, 3)
    });
});


function SidebarItem(marker, opts){
    var content = '<div class="eventDetailsContent hidden"><hr>' + "test" + "<div>";

    var header = document.createElement("div");
    header.className = "eventDetailsHeader";
    header.innerHTML = opts.title;

    var row = document.createElement("div");
    row.className = "eventDetails";
    row.innerHTML = header.outerHTML + content;

    row.onclick = function(){
        google.maps.event.trigger(marker, 'click');
    }

    this.button = row;
    document.getElementById("pinSidebar").appendChild(row);
}

SidebarItem.prototype.remove = function(){
    this.button.remove();
}

SidebarItem.prototype.expand = function() {
    for(var markerCounter in markerArray) {
        var details = $(markerArray[markerCounter].sidebarButton.button).find(".eventDetailsContent");
        if(! details.hasClass("hidden"))
            details.addClass('hidden');
    }
    $(this.button).find(".eventDetailsContent").toggleClass('hidden');
}

