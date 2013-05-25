$(document).ready(function() {
    var loadGpsPointList = function () {
        $.get('ajax/test.html', function(data) {
            $('.result').html(data);
            alert('Load was performed.');
        });
    }

    console.log(Routing.generate("lahaut_baloonwatcher_wsrecord_gpspointlistforlastscenario"));
});

google.load("earth", "1");
google.setOnLoadCallback(init);

function init() {
    google.earth.createInstance('map3d', initCB, failureCB);
}

function initCB(instance) {
    ge = instance;
    ge.getWindow().setVisibility(true);

    var i;

    for( i=0 ; i<10 ; i++) {
        // Create the placemark.
        var placemark = ge.createPlacemark('');

        // Set the placemark's location.
        var point = ge.createPoint('');
        point.setLatitude(48.036331);
        point.setLongitude(7.122809);
        point.setAltitudeMode(ge.ALTITUDE_ABSOLUTE);
        point.setAltitude(395 + 30 * i);
        placemark.setGeometry(point);

        // Add the placemark to Earth.
        ge.getFeatures().appendChild(placemark);
    }

}

function failureCB(errorCode) {
    console.log("failure");
}