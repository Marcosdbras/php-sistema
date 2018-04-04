
window.ve_gmap_visitor_position;
window.ve_gmaps = {};
window.ve_gmap_markers = {};
window.ve_gmap_polygons = {};
window.ve_gmap_polylines = {};
window.ve_gmap_infowindows = {};

function ve_map_update_positions_by_visitor(mapId)
{
    if (!window.ve_gmap_visitor_position) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                window.ve_gmap_visitor_position = position;
                var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                window.ve_gmaps[mapId].setCenter(coords);
            });
        }
        else {
            error('Geo Location is not supported');
            return false;
        }
    }
    else {
        var position = window.ve_gmap_visitor_position;
        var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        window.ve_gmaps[mapId].setCenter(coords);
    }
}

function ve_center_map(mapId, lat, long, targetId)
{
    window.ve_gmaps[mapId].setCenter(new google.maps.LatLng(lat, long));
    if (targetId) {
    	jQuery('html, body').animate({
            scrollTop: jQuery("#" + targetId).offset().top - 50
        }, 1000);
    }
}