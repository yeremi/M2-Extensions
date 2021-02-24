define([], function () {
    "use strict";
    return function locationAutoComplete() {
        var input = document.getElementById('location');
        new google.maps.places.Autocomplete(input);
    }
});