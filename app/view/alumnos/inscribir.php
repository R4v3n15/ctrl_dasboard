<?php $base_url = Config::get('URL'); ?>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4 pb-5 mb-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Registro de Alumnos</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-primary btn_forms"
                            id="form_tutor" 
                            data-form="tutor">Tutor</button>
                    <button class="btn btn-sm btn-outline-primary btn_forms"
                            id="form_alumno" 
                            data-form="alumno">Alumno</button>
                    <button class="btn btn-sm btn-outline-primary btn_forms"
                            id="form_estudios" 
                            data-form="estudios">Estudios</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="formulario">
                
            </div>
        </div>
    </main>
</div>


<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADKOI0m49Qp3bAb_lZt66MhZA2OMgM3lQ"></script> -->