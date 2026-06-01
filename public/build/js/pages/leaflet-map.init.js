/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
File: Leaflet init js
*/

// leaflet-map
var mymap = L.map('leaflet-map').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(mymap);


// leaflet-map-marker
var markermap = L.map('leaflet-map-marker').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(markermap);

L.marker([51.5, -0.09]).addTo(markermap);

L.circle([51.508, -0.11], {
    color: '#0ab39c',
    fillColor: '#0ab39c',
    fillOpacity: 0.5,
    radius: 500
}).addTo(markermap);

L.polygon([
    [51.509,-0.08],
    [51.503,-0.06],
    [51.51,-0.047]
],{
    color:'#405189',
    fillColor:'#405189'
}).addTo(markermap);


// popup map
var popupmap = L.map('leaflet-map-popup').setView([51.505,-0.09],13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    maxZoom:18,
    attribution:'&copy; OpenStreetMap contributors'
}).addTo(popupmap);

L.marker([51.5,-0.09]).addTo(popupmap)
.bindPopup("Hello World");


// group control
var cities = L.layerGroup();

L.marker([39.61,-105.02])
.bindPopup('Littleton')
.addTo(cities);

L.marker([39.74,-104.99])
.bindPopup('Denver')
.addTo(cities);

var grayscale=L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
);

var streets=L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
);

var layergroupcontrolmap=L.map(
'leaflet-map-group-control',
{
center:[39.73,-104.99],
zoom:10,
layers:[streets,cities]
});

var baseLayers={
"Grayscale":grayscale,
"Streets":streets
};

var overlays={
"Cities":cities
};

L.control.layers(
baseLayers,
overlays
).addTo(layergroupcontrolmap);