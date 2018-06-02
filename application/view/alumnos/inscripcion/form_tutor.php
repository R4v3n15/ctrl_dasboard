<form id="tutorForm" method="POST" class="form-horizontal" accept-charset='utf-8'>
    <h5 class="text-info text-center">DATOS DEL TUTOR: </h5>
    <hr>
    <div class="row justify-content-center">
        <div class="col-6 text-center">
            <div class="form-group row justify-content-center">
                <label class="col-sm-5 col-md-4 col-lg-3">¿Tiene Tutor?: </label>
                <div class="col-sm-7 col-md-8 col-lg-5">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="tutor_yes" name="hasTutor" checked 
                               class="custom-control-input has_tutor" value="si">
                        <label class="custom-control-label" for="tutor_yes">Si</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="tutor_not" name="hasTutor" 
                               class="custom-control-input has_tutor" value="no">
                        <label class="custom-control-label" for="tutor_not">No</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="info_tutor">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" 
                               class="form-control form-control-sm data_tutor text-center" 
                               id="surname_t" 
                               name="apellido_pat"
                               pattern="[a-zA-Z\s]{3,60}" 
                               placeholder="Apellido Paterno" 
                               autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" 
                               class="form-control form-control-sm data_tutor text-center" 
                               id="lastname_t" 
                               name="apellido_mat"
                               pattern="[a-zA-Z\s]{3,60}" 
                               placeholder="Apellido Materno" 
                               autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-sm-12">
                      <input type="text" 
                             class="form-control form-control-sm data_tutor text-center" 
                             id="name_tutor" 
                             name="nombre_tutor"
                             pattern="[a-zA-Z\s]{3,80}"
                             placeholder="Nombre" 
                             autocomplete="off">
                        <input type="hidden" class="form-control form-control-sm data_tutor" id="tutor_id" 
                               name="tutor_id">
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 text-center d-none bg-light pb-2 mb-2" id="exist_tutor">
                <p class="mb-0">¿Este es el tutor que quiere registrar?</p>
                <label>
                    <strong>Nombre: </strong><span id="tutor-name" class="text-info"></span>
                </label>
                <label>
                    <strong>Ocupación: </strong><span id="tutor-job"></span>
                </label><br>
                <button type="button" id="notUseTutor" class="btn btn-info btn-sm px-3 mr-3">No</button>
                <button type="button" id="useTutor" class="btn btn-secondary btn-sm px-4">Si</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="form-group row">
                    <label for="parentesco" class="col-sm-12 control-label">Parentesco: </label>
                    <div class="col-sm-12">
                        <select class="form-control form-control-sm data_tutor" id="relation" name="parentesco">
                            <option value="" hidden>Seleccione...</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            <option value="Abuelo(a)">Abuelo(a)</option>
                            <option value="Hermano(a)">Hermano(a)</option>
                            <option value="Tio(a)">Tío(a)</option>
                            <option value="Tutor">Tutor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group row">
                    <label for="ocupacion" class="col-sm-12 control-label">Ocupación: </label>
                    <div class="col-sm-12">
                        <input type="text" 
                               class="form-control form-control-sm data_tutor" 
                               id="ocupacion" 
                               name="ocupacion"
                               pattern="[a-zA-Z\s]{3,80}"
                               placeholder="Trabajo como.." 
                               autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">                  
                <div class="form-group row">
                    <label for="telcel" class="col-sm-12 control-label">Tel. Celular: </label>
                    <div class="col-sm-12">
                        <input type="tel" 
                               class="form-control form-control-sm data_tutor" 
                               id="tel_celular" 
                               name="tel_celular" 
                               placeholder="983 100 1020"
                               autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="form-group row">
                    <label for="inputtel" class="col-sm-12 control-label">Tel. Casa: </label>
                    <div class="col-sm-12">
                        <input type="tel" 
                               class="form-control form-control-sm data_tutor" 
                               id="tel_casa" 
                               name="tel_casa" 
                               placeholder="83 100 1122" 
                               autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group row">
                    <label class="col-md-12 control-label">Tel. Alternativo:</label>
                    <div class="col-md-12">
                        <input type="tel" 
                               class="form-control form-control-sm data_tutor"
                               id="tel_alterno"
                               name="tel_alterno" 
                               placeholder="983 000 1122" 
                               autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group row">
                    <label class="col-md-12 control-label">Parentesco:</label>
                    <div class="col-md-12">
                        <select class="form-control form-control-sm data_tutor" id="parent_alt" name="parentesco_alterno">
                            <option value="" hidden>Seleccione...</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            <option value="Abuelo(a)">Abuelo(a)</option>
                            <option value="Hermano(a)">Hermano(a)</option>
                            <option value="Hermano(a)">Tío(a)</option>
                            <option value="Tutor">Tutor</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
                        
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="form-group border bg-light px-2 pt-1 pb-3">
                    <label for="inpudir" class="control-label">Direccion: </label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control form-control-sm data_tutor" 
                               id="street" 
                               name="calle" 
                               placeholder="Calle" 
                               autocomplete="off">

                        <input type="text" 
                               class="form-control form-control-sm data_tutor" 
                               id="number" 
                               name="numero" 
                               placeholder="Numero" 
                               autocomplete="off">

                        <input type="text" 
                               class="form-control form-control-sm data_tutor" 
                               id="between" 
                               name="entre" 
                               placeholder="Entre" 
                               autocomplete="off">

                        <input type="text" 
                               class="form-control form-control-sm data_tutor" 
                               id="colony" 
                               name="colonia" 
                               placeholder="Colonia" 
                               autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-6 col-sm-5 col-md-4 col-lg-3 text-center">
                <input type="button" 
                       class="btn btn-warning" 
                       id="cancelRegister" 
                       data-steep="0"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="Cancelar Registro" 
                       value="Cancelar">
            </div>
            <div class="col-6 col-sm-5 col-md-4 col-lg-3 text-center">
                <input type="button" class="btn btn-primary" id="createTutor" value="Guardar" />
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 d-none text-center" id="continue">
            <a href="#" class="btn btn-sm btn-info btn_forms" id="form_alumno" data-form="alumno">Continuar <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</form> 