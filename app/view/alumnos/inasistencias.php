<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Registro de Inasistencias</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-success" id="create_register">Nuevo Registro</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="example" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr class="">
                            <th class="text-center">No.</th>
                            <th class="text-center">Alumno</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Maestro</th>
                            <th class="text-center">Motivo de Falta</th>
                            <th class="text-center">Comentario del Maestro</th>
                            <th class="text-center">Fecha de Contacto</th>
                            <th class="text-center">Fecha de Regreso</th>
                        </tr>
                    </thead>
                </table>
                
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="new_register_modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title mb-0 text-white" id="ModalCenterTitle">Registro de Inasistencia</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmNewRegister" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-8 px-2">
                        <div class="form-group">
                            <label for="inputname">Alumno: </label>
                            <select class="form-control form-control-sm"  name="alumno" id="alumno" style="width: 100%;" required="true">
                                <option hidden>Seleccione alumno...</option>
                                <?php if (count($this->alumnos) > 0): ?>
                                    <?php foreach ($this->alumnos as $index => $alumno): ?>
                                        <option value="<?= $alumno->student_id; ?>"><?= $alumno->name; ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 px-2">
                        <div class="form-group">
                            <label for="fecha_falta">Fecha de Inasistencia: </label>
                            <input type="text" 
                                   id="fecha_falta" 
                                   class="form-control form-control-sm"
                                   placeholder="Inicia.." 
                                   name="fecha_falta"
                                   autocomplete="off" 
                                   required>
                        </div>
                    </div>

                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="inputname">Maestro: </label>
                            <?php if ((int)$this->user_type !== 3): ?>
                            <select class="form-control form-control-sm"  name="maestro" id="maestro" style="width: 100%;" required="true">
                                <option>Seleccione maestro...</option>
                                <?php foreach ($this->maestros as $index => $maestro): ?>
                                    <option value="<?= $maestro->user_id; ?>"><?= $maestro->name; ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php else: ?>
                                <?php foreach ($this->maestros as $maestro): ?>
                                    <?php if ((int)$this->current === (int)$maestro->user_id): ?>
                                    <label><u><?= $maestro->name; ?></u></label>
                                    <input type="hidden" name="maestro" value="<?= $maestro->user_id; ?>">
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="motivo_falta" class="control-label">Motivo de Falta: </label>
                            <textarea name="motivo_falta" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="comentario_maestro" class="control-label">Comentario del Maestro: </label>
                             <textarea name="comentario_maestro" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 px-2">
                        <div class="form-group mb-0">
                            <label for="fecha_contacto" class="control-label">Fecha de Contacto: </label>
                            <input type="text" 
                                   id="fecha_contacto" 
                                   class="form-control form-control-sm"
                                   placeholder="Finaliza.." 
                                   name="fecha_contacto"
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 px-2">
                        <div class="form-group mb-0">
                            <label for="fecha_regreso" class="control-label">Fecha de Regreso: </label>
                                <input type="text" 
                                       id="fecha_regreso" 
                                       class="form-control form-control-sm"
                                       placeholder="Finaliza.." 
                                       name="fecha_regreso"
                                       autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2 py-2 px-2">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat-lg" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" class="btn btn-info btn-sm btn-flat-lg">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>