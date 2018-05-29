<form id="studentForm" method="POST" class="form-horizontal" enctype="multipart/form-data">
    <h5 class="text-info text-center mb-0">DATOS DEL ALUMNO <small id="tutor_name"></small></h5>
    <hr>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-4">
            <div class="form-group row">
                <div class="col-sm-12">
                   <input type="text" 
                          class="form-control form-control-sm text-center" 
                          id="surname" 
                          name="surname" 
                          placeholder="Apellido Paterno"
                          pattern="[a-zA-Z\s]{2,60}" 
                          autocomplete="off" required>
                    <input type="hidden" class="form-control" id="tutor_id" name="tutor">
                    <input type="hidden" class="form-control" id="address_id" name="address">
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="text" 
                          class="form-control form-control-sm text-center" 
                          id="lastname" 
                          name="lastname" 
                          placeholder="Apellido Materno"
                          pattern="[a-zA-Z\s]{2,60}" 
                          autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="text" 
                           class="form-control form-control-sm text-center" 
                           id="name" 
                           name="name" 
                           placeholder="Nombre(s)"
                           pattern="[a-zA-Z\s]{3,60}" 
                           autocomplete="off" required>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-8 text-center d-none bg-light pb-2 mb-2" id="exist_student">
            <p class="mb-0">¿Este es el alumno que quiere registrar?</p>
            <label>
                <strong>Nombre: </strong><span id="student-name" class="text-info"></span><br>
                <strong>Grupo: </strong><span id="student-group"></span><br>
                <strong>Estado: </strong><span id="student-status"></span>
            </label><br>
            <button type="button" 
                   class="btn btn-sm btn-warning mr-3" 
                   id="cancelRegister" 
                   data-steep="1"
                   data-toggle="tooltip"
                   data-placement="bottom"
                   title="Cancelar Registro">Cancelar Registro</button>
            <button type="button" 
                    id="dissmiss"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Continuar Registro"  
                    class="btn btn-secondary btn-sm">Continuar Registro</button>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col-sm-6 col-md-4">
            <div class="form-group row">
                <label for="dia" class="col-12">Cumpleaños:</label>
                <div class="col-12 input-group">
                    <select class="form-control form-control-sm" name="day" required="true">
                        <option value="">Día...</option>
                        <?php 
                        for ($j=1; $j<=31; $j++){
                            echo "<option value='".$j."'>".$j."</option>";
                        }
                        ?>
                    </select>
                    <select class="form-control form-control-sm" name="month" required="true">
                        <option value="">Mes...</option>
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                    <select class="form-control form-control-sm" name="year" required="true">
                        <option value="">Año...</option>
                        <?php 
                            $thisYear = date("Y")-2;
                            $lastYear = $thisYear - 60;
                            for ($i=$thisYear; $i>=$lastYear; $i--){
                                echo "<option value='".$i."'>".$i."</option> ";
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group row">
                <label for="genero" class="col-12">Sexo: </label>
                <div class="col-12">
                    <select class="form-control form-control-sm" name="genero">
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-2">
            <div class="form-group row">
                <label for="edo_civil" class="col-12">Estado Civil: </label>
                <div class="col-12">
                    <select class="form-control form-control-sm" name="edo_civil">
                        <option value="Soltero(a)">Soltero(a)</option>
                        <option value="Casado(a)">Casado(a)</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group row">
                <label for="celular" class="col-12">Num. celular: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm" 
                           id="celular" 
                           name="celular" 
                           placeholder="983 100 1020" 
                           pattern="[0-9\s]{8,15}" 
                           autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group border bg-light px-2 pt-1 pb-3">
                <label for="inpudir" class="control-label">Direccion: </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="street_s" 
                           name="calle"
                           placeholder="Calle" 
                           autocomplete="off">

                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="number_s" 
                           name="numero"
                           placeholder="Numero" 
                           autocomplete="off">

                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="between_s" 
                           name="entre"
                           placeholder="Entre" 
                           autocomplete="off">

                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="colony_s" 
                           name="colonia"
                           placeholder="Colonia" 
                           autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="reference-addon1">Referencia de Domicilio: </span>
                    </div>
                    <input type="text" 
                           class="form-control" 
                           id="referencia" 
                           name="referencia" 
                           placeholder="Indique una referencia del domicilio" 
                           autocomplete="off"
                           aria-describedby="reference-addon1"
                           aria-label="Referencia">
                </div>
            </div>
        </div> 
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group row">
                <label class="col-sm-12 control-label">¿Tiene algún padecimiento/enfermedad?: </label>
                <div class="col-sm-12">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="isSick_not"value="No"
                               name="isSickness" class="custom-control-input isSick" checked="checked">
                        <label class="custom-control-label" for="isSick_not">No </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="isSick_yes"value="Si"
                               name="isSickness" class="custom-control-input isSick">
                        <label class="custom-control-label" for="isSick_yes">Si </label>
                    </div>
                </div>
            </div>
            <div class="form-group sicknes_detail d-none">
                <label class="col-sm-3 control-label">¿Especifique Cuál?: </label>
                <div class="col-sm-8">
                    <input type="text" 
                          class="form-control" 
                          id="padecimiento" 
                          name="padecimiento" 
                          placeholder="Especifique de que padece" autocomplete="off" />
                </div>
            </div>
            <div class="form-group sicknes_detail d-none">
                <label class="col-sm-3 control-label">¿Indique tratamiento?: </label>
                <div class="col-sm-8">
                    <input type="text" 
                          class="form-control" 
                          id="tratamiento" 
                          name="tratamiento" 
                          placeholder="¿Qué hacer en dado caso?" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group row">
                <label class="col-12 control-label">¿Es de familia Homestay?: </label>
                <div class="col-12">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="homestay_not" value="0"
                               name="homestay" class="custom-control-input" checked="checked">
                        <label class="custom-control-label" for="homestay_not">No </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="homestay_yes" value="1"
                               name="homestay" class="custom-control-input">
                        <label class="custom-control-label" for="homestay_yes">Si </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group row">
                <label class="col-12">¿Entregó acta de nacimiento?: </label>
                <div class="col-12">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="acta_not" value="0"
                               name="acta" class="custom-control-input" checked="checked">
                        <label class="custom-control-label" for="acta_not">No </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="acta_yes" value="1"
                               name="acta" class="custom-control-input">
                        <label class="custom-control-label" for="acta_yes">Si </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group row">
                <label class="col-12">¿Requiere facturaci&oacute;n?: </label>
                <div class="col-12">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="invoice_not" value="0"
                               name="facturacion" class="custom-control-input" checked="checked">
                        <label class="custom-control-label" for="invoice_not">No </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="invoice_yes" value="1"
                               name="facturacion" class="custom-control-input">
                        <label class="custom-control-label" for="invoice_yes">Si </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="row mb-3">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-12 control-label">Subir Foto:</label>
                <div class="col-10 col-sm-6 col-md-4">
                    <input type="file" id="avatar" name="avatar_file" class="form-control form-control-sm" />
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group row">
                <label class="col-12 control-label">Comentario:</label>
                <div class="col-12">
                    <textarea name="comentario" 
                              class="form-control"
                              placeholder="Escriba aquí alguna observación sobre el alumno..." 
                              rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-6 col-sm-5 col-md-4 col-lg-3 text-center">
            <input type="button" 
                   class="btn btn-warning" 
                   id="cancelRegister" 
                   data-steep="1"
                   data-toggle="tooltip"
                   data-placement="bottom"
                   title="Cancelar Registro" 
                   value="Cancelar">
        </div>
        <div class="col-6 col-sm-5 col-md-4 col-lg-3 text-center">
            <input type="button" class="btn btn-primary" id="createStudent" value="Guardar">
        </div>
    </div>
</form>