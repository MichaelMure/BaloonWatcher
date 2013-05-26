var $gpsPointList = null;
var ge = null;

$(document).ready(function() {
    loadGpsPointList();
});

google.load("earth", "1");
google.setOnLoadCallback(init);

var loadGpsPointList = function () {
    var gpsPointListUrl = Routing.generate("lahaut_baloonwatcher_wsrecord_gpspointlistforlastscenario");

    $.get(gpsPointListUrl, function(data) {
        $gpsPointList = data;
    });
}

var updateGpsPointList = function () {
    var gpsPointListUrl = Routing.generate("lahaut_baloonwatcher_wsrecord_gpspointlistforlastscenario");

    $.get(gpsPointListUrl, function(data) {
        $gpsPointList = data;

        displayGpsPointList();
    });
}

function init() {
    google.earth.createInstance('map3d', initCB, failureCB);
}

function initCB(instance) {
    ge = instance;
    ge.getWindow().setVisibility(true);

    displayGpsPointList();
    setInterval(updateGpsPointList, 2000);
}

function displayGpsPointList() {
    removeAllPlacemark();
    for (var key in $gpsPointList) {
        // Create the placemark.
        var placemark = ge.createPlacemark('');

        // Set the placemark's location.
        var point = ge.createPoint('');
        point.setLatitude($gpsPointList[key].latitude);
        point.setLongitude($gpsPointList[key].longitude);
        point.setAltitudeMode(ge.ALTITUDE_ABSOLUTE);
        point.setAltitude($gpsPointList[key].altitude);
        placemark.setGeometry(point);

        // Add the placemark to Earth.
        ge.getFeatures().appendChild(placemark);
    }
}

function failureCB(errorCode) {
    console.log("failure");
}

function removeAllPlacemark() {
    var features = ge.getFeatures();
    while (features.getFirstChild()) {
        features.removeChild(features.getFirstChild());
    }
}

