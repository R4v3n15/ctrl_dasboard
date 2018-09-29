                <form action="" method="post" class="form-horizontal">   
                    <legend><h4 class="bg-info">ANTECEDENTES: </h4></legend>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="ocupacion" class="col-sm-4">Ocupación: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="ocupacion">
                                        <option class="student" value="Estudiante">Estudio</option>
                                        <option class="worker" value="Trabajador">Trabajo</option>
                                        <option class="worker" value="Ninguno">Ninguno</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div id="job" class="form-group">
                                <label for="lugar" class="col-sm-3">Donde estudia/trabaja: </label>
                                <div class="col-sm-9">
                                    <input type="text" 
                                           class="form-control" 
                                           id="lugar" 
                                           name="lugar" 
                                           placeholder="Estudio/Trabajo en">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">          
                            <div class="form-group">
                                <label for="nivel" class="col-sm-4">Nivel de estudios: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="nivel">
                                        <option value="">Seleccione...</option>
                                        <option value="Preescolar">Preescolar</option>
                                        <option value="Primaria">Primaria</option>
                                        <option value="Secundaria">Secundaria</option>
                                        <option value="Bachillerato">Bachillerato</option>
                                        <option value="Licenciatura">Licenciatura</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7" id="grados">
                            <div class="form-group grade">
                                <label class="col-sm-3">Grado: </label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="grado">
                                        <option value="">Seleccione...</option>
                                        <option id="a" value="Primer Año">Primer Año.</option>
                                        <option id="b" value="Segundo Año">Segundo Año.</option>
                                        <option id="c" value="Tercer Año">Tercer Año.</option>
                                        <option id="d" value="Cuarto Año">Cuarto Año.</option>
                                        <option id="e" value="Quinto Año">Quinto Año.</option>
                                        <option id="f" value="Sexto Año">Sexto Año.</option>
                                        <option id="g" value="Concluido">Concluido.</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-4">¿Ha tomado algún cusro de inglés anteriormente?: </label>
                                <div class="col-sm-5">
                                    <div class="radio radio-info">
                                    <label> No
                                        <input type="radio" id="optionNo" checked="checked" value="no" name="optionRadio">
                                        <span class="circle"></span><span class="check"></span>
                                    </label>
                                    <label></label>
                                    <label> SI
                                        <input type="radio" id="optionSi" value="si" name="optionRadio">
                                        <span class="circle"></span><span class="check"></span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" id="describa">
                                <label class="col-sm-2">Describa: </label>
                                <div class="col-sm-10">
                                    <input type="text" 
                                           class="form-control" 
                                           id="cursoanterior" 
                                           name="cursoanterior" 
                                           placeholder="Descripcion de cursos anteriores">
                                </div>
                            </div>
                        </div>
                    </div>

                    <legend><h4 class="bg-info">CURSO A TOMAR:</h4></legend> 

                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="col-sm-4">Nivel: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="curso">
                                        <option value="">Seleccione...</option>
                                        <option value="1">English Club</option>
                                        <option value="2">Primaria</option>
                                        <option value="3">Adolescentes</option>
                                        <option value="4">Adultos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Grupo: </label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="thisDataGroup" name="grupo">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="datosCurso"></div>
                        <div class="clearfix"></div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-4">Cuando desea iniciar: </label>
                                <div class="date col-sm-7">
                                    <input type="date" class="form-control" name="fecha_init">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="col-sm-4">¿Entregó acta de nacimiento?: </label>
                        <div class="col-sm-5">
                            <div class="radio radio-info">
                                <label> No
                                    <input type="radio" checked="checked" value="0" name="acta">
                                    <span class="circle"></span><span class="check"></span>
                                </label>
                                <label></label>
                                <label> SI
                                    <input type="radio" value="1" name="acta">
                                    <span class="circle"></span><span class="check"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4">¿Requiere facturaci&oacute;n?: </label>
                        <div class="col-sm-5">
                            <div class="radio radio-info">
                                <label> No
                                    <input type="radio" checked="checked" value="0" name="facturacion">
                                    <span class="circle"></span><span class="check"></span>
                                </label>
                                <label></label>
                                <label> SI
                                    <input type="radio" value="1" name="facturacion">
                                    <span class="circle"></span><span class="check"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-xs-offset-4 text-center">
                            <input type="submit" class="btn btn-sm btn-raised btn-primary" value="GUARDAR">
                         </div>
                    </div>
                </form>