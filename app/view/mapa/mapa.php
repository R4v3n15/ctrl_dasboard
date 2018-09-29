<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <label for="inpudir" class="control-label">Direccion: </label>
                    </div>
                    <div class="input-group col-12 mb-2">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="street"
                               value="<?= $this->address->street; ?>" 
                               placeholder="Nombre de calle">

                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="number"
                               value="<?= $this->address->number; ?>" 
                               placeholder="Numero de calle">

                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="between"
                               value="<?= $this->address->between; ?>" 
                               placeholder="Entre calle 1 y calle 2">

                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="colony"
                               value="<?= $this->address->colony; ?>" 
                               placeholder="Colonia">
                    </div>
                    <div class="col-10">
                        <button type="button" class="btn btn-success btn-sm mr-3">Guardar Croquis</button>
                        <input type="hidden" 
                                class="form-control form-control-sm" 
                                value="<?= $this->address->latitude; ?>" 
                                name="latitud" id="lat">
                        <input type="hidden" 
                                class="form-control form-control-sm" 
                                value="<?= $this->address->longitud; ?>" 
                                name="longitud" id="lng">
                        <input type="hidden" 
                                class="form-control form-control-sm" 
                                value="<?= $this->address->name; ?>" 
                                name="alumno" id="student">
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="btn btn-secondary btn-sm" title="Ayuda">
                            <i class="fas fa-question"></i>
                        </button>
                    </div>
                </div>                
            </div>
            <div class="col-md-12" id="map" style="height: 80vh">
                
            </div>
        </div>
    </main>
</div>


<script>
    function initMap() {
        var latitud  = parseFloat(document.getElementById('lat').value);
        var longitud = parseFloat(document.getElementById('lng').value);

        var map = new google.maps.Map(document.getElementById('map'), {
            minZoom: 10,
            zoom: 14,
            center: {lat: latitud, lng: longitud}
        });

        var marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map
        });

        var student = document.getElementById('student').value;

        setCoords(map);

        var infowindow = new google.maps.InfoWindow({
            content: '<p><strong>Ubicación de:</strong> '+student+'</p>'
        });

        infowindow.open(map, marker);

        // google.maps.event.addListener(marker, 'mouseover', function() {
        //     infowindow.open(map, marker);
        // });

        // map.addListener('click', function(e) {
        //     map.setZoom(map.getZoom() + 1);
        // });

        map.addListener('click', function(e) {
            // map.setZoom(map.getZoom() + 1);
            placeMarkerAndPanTo(e.latLng, map, marker);
        });
}

function placeMarkerAndPanTo(latLng, map, marker) {
    marker.setPosition(latLng);
    map.panTo(latLng);
    map.setCenter(latLng);

    setCoords(map);
}

function setCoords(map){
    document.getElementById('lat').value = map.getCenter().lat();
    document.getElementById('lng').value = map.getCenter().lng();
}

</script>