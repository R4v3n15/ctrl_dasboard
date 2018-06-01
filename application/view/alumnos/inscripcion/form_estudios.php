<form id="studiesForm" method="post" class="form-horizontal">   
    <h5 class="text-info text-center">INFORMACIÓN ACADEMICA <small id="student_name"></small></h5>
    <hr>
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-7">
            <div class="form-group">
                <label for="ocupacion" class="col-12">Ocupación: </label>
                <div class="col-12 input-group">
                    <select class="form-control form-control-sm" id="ocupation" name="ocupacion">
                        <option value="Ninguno" hidden="">Seleccione..</option>
                        <option value="Estudiante">Estudio</option>
                        <option value="Trabajador">Trabajo</option>
                        <option value="Ninguno">Ninguno</option>
                    </select>
                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="lugar_trabajo" 
                           name="lugar_trabajo" 
                           placeholder="Lugar de trabajo/estudio"
                           autocomplete="off">
                    <input type="hidden" class="form-control" name="alumno" id="student_id">
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-5">          
            <div class="form-group">
                <label for="nivel" class="col-12">Nivel de estudios: </label>
                <div class="col-12 input-group">
                    <select class="form-control form-control-sm" id="nivel" name="nivel_estudio">
                        <option value="">Seleccione...</option>
                        <option value="Preescolar">Preescolar</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                        <option value="Bachillerato">Bachillerato</option>
                        <option value="Licenciatura">Licenciatura</option>
                    </select>
                    <select class="form-control form-control-sm" name="grado_estudio">
                        <option value="" hidden>Seleccione...</option>
                        <option value="Primer Año">Primer Año.</option>
                        <option value="Segundo Año">Segundo Año.</option>
                        <option value="Tercer Año">Tercer Año.</option>
                        <option class="extra d-none" value="Cuarto Año">Cuarto Año.</option>
                        <option class="extra d-none" value="Quinto Año">Quinto Año.</option>
                        <option class="extra d-none" value="Sexto Año">Sexto Año.</option>
                        <option value="Concluido">Concluido.</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="curso_previo" class="col-sm-4">
                    ¿Tomó algún curso de inglés previamente?: 
                </label>
                <div class="col-sm-5">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="0" id="optionNo" name="curso_previo" 
                               class="custom-control-input isPrevious" checked="checked">
                        <label class="custom-control-label" for="optionNo">No </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="1" id="optionSi" name="curso_previo" 
                               class="custom-control-input isPrevious">
                        <label class="custom-control-label" for="optionSi">Si </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 d-none" id="describa">
            <div class="form-group">
                <label class="col-12">Descripción del curso anterior: </label>
                <div class="col-12">
                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="cursoanterior" 
                           name="description_previo" 
                           placeholder="Descripcion de cursos anteriores">
                </div>
            </div>
        </div>
    </div>
    
    <hr>

    <h4 class="text-info text-center">CURSO A TOMAR:</h4>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-12 control-label">Curso: </label>
                <div class="col-12 input-group">
                    <input type="hidden" name="clase" id="clase_id">
                    <select class="form-control form-control-sm" id="course" name="curso" required>
                        <option value="" hidden>Seleccione curso...</option>
                        <?php  
                        if ($this->cursos) {
                            foreach ($this->cursos as $curso) {
                                echo '<option value="'.$curso->course_id.'">'
                                        .ucwords(strtolower($curso->course)).
                                     '</option>';
                            }
                        } else {
                            echo '<option>No hay cursos registrados</option>';
                        }
                        ?>
                        <option value="0">En Espera</option>
                    </select>

                    <select class="form-control form-control-sm" id="groups" name="grupo" required>
                        <option value="" hidden>Seleccione grupo...</option>
                    </select>
                </div>
            </div>
            <div class="form-group ml-3" id="clasedata">
                <span class="col-sm-6"   id="clase_name"></span>
                <span class="col-sm-6"   id="date_start"></span><br>
                <span class="col-sm-6"   id="schedule"></span>
                <span class="col-sm-6"   id="date_end"></span><br>
                <span class="col-sm-12"  id="days"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-12 control-label">Inicio Alumno:</label>
                <div class="col-12">
                    <input type="text" 
                           id="fecha_inicio" 
                           class="form-control form-control-sm datetimepicker-input"
                           data-toggle="datetimepicker" 
                           data-target="#fecha_inicio"
                           placeholder="Cuándo inicia el alumno" 
                           name="f_inicio_alumno">
                </div>
            </div>
        </div>  
    </div>
    
    <div class="row justify-content-center">
        <div class="col-6 col-sm-5 col-md-4 col-lg-3 text-center">
            <input type="button" class="btn btn-warning" id="cancelRegister" data-steep="2" value="Cancelar">
        </div>
        <div class="col-6 col-sm-5 col-md-4 col-lg-3 text-center">
            <input type="button" class="btn btn-primary" id="createStudies" value="Guardar">
        </div>
    </div>
</form>