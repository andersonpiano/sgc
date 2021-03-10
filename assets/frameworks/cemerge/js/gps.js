document.addEventListener("DOMContentLoaded", function(event) {
    navigator.geolocation.getCurrentPosition(set_fields);
});

function set_fields(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    document.getElementById('latitude').value = latitude;
    document.getElementById('longitude').value = longitude;
    console.log(latitude);
    console.log(longitude);
}

function show_map(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude);
    console.log(longitude);
}