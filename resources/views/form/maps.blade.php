<div id="map" style="height: 300px"></div>
<input type="hidden" readonly name="lat" id="mapLat" value="{{ $lat ?? '' }}">
<input type="hidden" readonly name="long" id="mapLng" value="{{ $long ?? '' }}">
@push('css')
@endpush
@push('scripts')
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('app.MAP_API_KEY') }}&libraries=places&callback=initAutocomplete"
    async defer></script>
<script>
    // Initialize and add the map
function initAutocomplete() {
    var lat_init = $('#mapLat').val();
    var lng_init = $('#mapLng').val();
    var hopee = {lat: 10.8552169, lng: 106.6292814};
    if (lat_init && lng_init) {
        hopee = {lat: Number(lat_init), lng: Number(lng_init)};
    }
    // The location of Hopee
    // The map, centered at Hopee
    var map = new google.maps.Map(
        document.getElementById('map'), {
            zoom: 15,
            center: hopee,
            streetViewControl: false,
            mapTypeControl: false,
            gestureHandling: 'greedy',
        });
    // Create the search box and link it to the UI element.
    var input = document.getElementById('searchLocationMap');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    // The marker, positioned at init
    var marker = new google.maps.Marker({
        position: hopee,
        map: map,
        draggable: true,
    });

    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        var place = places[0];

        // // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
        }

        marker.setPosition(place.geometry.location);
        lat = marker.getPosition().lat();
        lng = marker.getPosition().lng();
        appendLatLng(lat,lng);

        if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
        } else {
            bounds.extend(place.geometry.location);
        }
        map.fitBounds(bounds);
    });

    var lat = marker.getPosition().lat();
    var lng = marker.getPosition().lng();

    window.google.maps.event.addListener(marker, 'dragend', (e) => {
        lat = marker.getPosition().lat();
        lng = marker.getPosition().lng();
        appendLatLng(lat,lng);
    });

    appendLatLng(lat,lng);
}

function appendLatLng(lat,lng) {
    $('#mapLat').val(lat);
    $('#mapLng').val(lng);
}
</script>
@endpush