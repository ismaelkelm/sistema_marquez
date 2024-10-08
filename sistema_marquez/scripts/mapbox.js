
mapboxgl.accessToken = 'pk.eyJ1IjoiY29ycmVudGluby1hbS00NSIsImEiOiJjbTFwYzVoa3cwMnUyMmpvZGJxeW0yc2UwIn0.ul07FgxGsvS-xxXrIJkKOQ';
const map = new mapboxgl.Map({
    container: 'map', // container ID
    center: [-74.5, 40], // starting position [lng, lat]. Note that lat must be set between -90 and 90
    zoom: 9 // starting zoom
});
