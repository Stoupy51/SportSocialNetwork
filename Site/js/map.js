
var searchBar = document.getElementById("header-searchbar");
searchBar.placeholder = "Renseignez une adresse de départ pour les trajets";
var baseCoords = [49.258322,4.02402];
if (searchBar.value != "") {
	// https://api-adresse.data.gouv.fr/search/?q=
	new AjaxRequest({
		url: "https://api-adresse.data.gouv.fr/search/",
		method: 'get',
		parameters: {
			q: searchBar.value,
		},
		onSuccess: function(res) {
			let obj = JSON.parse(res).features;
			baseCoords = [
				obj[0].geometry.coordinates[1],
				obj[0].geometry.coordinates[0]
			];
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

var map = L.map('map').setView([49.258329,4.031696], 13);

var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoibWlzdWtxIiwiYSI6ImNrd3c2YXJ1YzAwbHMydmxjMzBmYXQ1bnYifQ.dkYf1un4SdosYSzVD3KVhQ'
}).addTo(map);

document.getElementById("map").style.position = "fixed";

var oldRoute;
function trajet(long, lat) {
	if (oldRoute != null) {
		map.removeControl(oldRoute);
	}
	oldRoute = L.Routing.control({
		waypoints: [
			L.latLng(baseCoords[0],baseCoords[1]),	//Départ
			L.latLng(long,lat)						//Arrivée
		]
	}).addTo(map);
}