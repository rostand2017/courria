// recupération de la position en js

if( navigator.geolocation){
    navigator.geolocation.getCurrentPosition(function (position) {
        alert("Latitude: " + position.coords.latitude +
            "<br>Longitude: " + position.coords.longitude);
    });
}