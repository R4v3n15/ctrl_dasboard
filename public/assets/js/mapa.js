var map;
var geocoder;
var centerChangedLast;
var reverseGeocodedLast;
var currentReverseGeocodeResponse;

function init_map() {
    var lati = $('#lat').val(),
        long = $('#lng').val();

    var latlng = new google.maps.LatLng(19.579994462915835, -88.04420235898436);
    var myOptions = {
        zoom: 3,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map($("#map_canvas"), myOptions);
    geocoder = new google.maps.Geocoder();
    setupEvents();
    centerChanged();
    var opt = { minZoom: 14 };
    map.setOptions(opt);
}

function setupEvents() {
    reverseGeocodedLast = new Date();
    centerChangedLast = new Date();

    setInterval(function() {
      if((new Date()).getSeconds() - centerChangedLast.getSeconds() > 1) {
        if(reverseGeocodedLast.getTime() < centerChangedLast.getTime())
          reverseGeocode();
      }
    }, 1000);

    google.maps.event.addListener(map, 'zoom_changed', function() {
      document.getElementById("zoom_level").innerHTML = map.getZoom();
    });

    google.maps.event.addListener(map, 'center_changed', centerChanged);

    google.maps.event.addDomListener($('#crosshair'),'dblclick', function() {
       map.setZoom(map.getZoom() + 1);
    });
}

function getCenterLatLngText() {
    return '(' + map.getCenter().lat() +', '+ map.getCenter().lng() +')';
}

function centerChanged() {
    centerChangedLast = new Date();
    var latlng = getCenterLatLngText();
    var lat    = map.getCenter().lat();
    var lng    = map.getCenter().lng();
    $('#lat').val(lat);
    $('#lng').val(lng);
    $('#formatedAddress').html('');
    currentReverseGeocodeResponse = null;
}

function reverseGeocode() {
    reverseGeocodedLast = new Date();
    geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
}

function reverseGeocodeResult(results, status) {
    currentReverseGeocodeResponse = results;
    if(status == 'OK') {
        if(results.length == 0) {
            $('#formatedAddress').html('None');
        } else {
            $('#formatedAddress').html(results[0].formatted_address);
        }
    } else {
        $('#formatedAddress').text('Error');
    }
}

function geocode() {
    var address = $("#address").val()+ "Felipe Carrillo PuertoFelipe Carrillo Puerto, Q.R., México";
    geocoder.geocode(
        {
            'address': address,
            'partialmatch': true
        }, 
        geocodeResult);
}

function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
        map.fitBounds(results[0].geometry.viewport);
    } else {
      alert("No es posible cargar el mapa por la siguiente razón: " + status);
    }
}

function addMarkerAtCenter() {
    var marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map
    });

    var text = 'Lat/Lng: ' + getCenterLatLngText();
    if(currentReverseGeocodeResponse) {
        var addr = '';
        if(currentReverseGeocodeResponse.size == 0) {
            addr = 'None';
        } else {
            addr = currentReverseGeocodeResponse[0].formatted_address;
        }
        text = text + '<br>' + 'Dirección: <br>' + addr;
    }

    var infowindow = new google.maps.InfoWindow({ content: text });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });
}


$(document).ready(function(){
    // init_map();
});
