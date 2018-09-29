<div class="row">
    <div class="col-md-12">
    <input type="button"
           id="btn_marker" 
           value="Marcar" 
           onclick="addMarkerAtCenter()" 
           class="btn btn-info btn-sm" />
    </div>
    <div class="col-md-12">
        <div class="coordinates">
            <?php foreach ($alumno->address as $address): ?>
            <input type="hidden" 
                   id="lat" 
                   name="lat"
                   value="<?= $address->latitud; ?>" 
                   class="form-control" 
                   onclick="select()" />
            <input type="hidden" 
                   id="lng" 
                   name="lng"
                   value="<?= $address->longitud; ?>" 
                   class="form-control" 
                   onclick="select()" />
            <?php endforeach ?>
        </div>
        <div id="map">
            <div id="map_canvas"></div>
            <div id="crosshair">
                <span class="glyphicon glyphicon-move"></span>
            </div>
            <span id="zoom_level"></span>
        </div>
        <div class="address">
            <span id="formatedAddress">-</span>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADKOI0m49Qp3bAb_lZt66MhZA2OMgM3lQ"></script>
<!-- <script src="<?php //echo Config::get('URL'); ?>assets/js/mapa.js"></script>
<script>
  $(document).ready(function(){
      init_map();
    });
</script> -->
