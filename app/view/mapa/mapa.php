<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <label for="inpudir" class="control-label">Direccion: </label>
                    </div>
                    <div class="input-group col-sm-6 col-md-3 col-lg-2 mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <span class="input-group-text" id="addon1">Calle:</span>
                            </div>
                            <input type="text" 
                               class="form-control form-control-sm" 
                               id="street"
                               aria-describedby="addon1"
                               value="<?= $this->address->street; ?>" 
                               placeholder="Nombre de calle">
                        </div>
                    </div>
                    <div class="input-group col-sm-6 col-md-3 col-lg-2 mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <span class="input-group-text" id="addon2">Número:</span>
                            </div>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   id="number"
                                   aria-describedby="addon2"
                                   value="<?= $this->address->number; ?>" 
                                   placeholder="Numero de calle">
                        </div>
                    </div>
                    <div class="input-group col-sm-6 col-md-3 col-lg-4 mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <span class="input-group-text" id="addon3">Entre:</span>
                            </div>
                            <input type="text" 
                               class="form-control form-control-sm" 
                               id="between"
                               aria-describedby="addon3"
                               value="<?= $this->address->between; ?>" 
                               placeholder="Entre calle 1 y calle 2">
                        </div>
                    </div>
                    <div class="input-group col-sm-6 col-md-3 col-lg-4 mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <span class="input-group-text" id="addon4">Colonia:</span>
                            </div>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   id="colony"
                                   aria-describedby="addon4"
                                   value="<?= $this->address->colony; ?>" 
                                   placeholder="Colonia">
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="button" 
                                class="btn btn-success btn-sm mr-3"
                                id="saveLocation">Guardar Ubicación</button>
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
                                value="<?= $this->address->user; ?>" 
                                name="user" id="user">
                        <input type="hidden" 
                                class="form-control form-control-sm" 
                                value="<?= $this->address->user_type; ?>" 
                                name="user_type" id="user_type">
                        <input type="hidden" 
                                class="form-control form-control-sm" 
                                value="<?= $this->address->name; ?>" 
                                name="nombre" id="student_name">
                    </div>
                    <div class="col-12 text-right d-none">
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

    var student = document.getElementById('student_name').value;

    setCoords(map);

    var infowindow = new google.maps.InfoWindow({
        content: '<p><strong>Alumno:</strong> '+student+'</p>'
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

function updateStudentLocation(){
    var latitud  = parseFloat(document.getElementById('lat').value);
    var longitud = parseFloat(document.getElementById('lng').value);

    console.log(latitud, longitud);
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