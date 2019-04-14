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
                <?php if(count($this->absences) > 0): ?>
                <table id="example" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr class="">
                            <th class="text-center" width="50">No.</th>
                            <th class="text-center" width="90">Alumno</th>
                            <th class="text-center" width="80">Fecha</th>
                            <th class="text-center" width="90">Grupo</th>
                            <th class="text-center" width="90">Maestro</th>
                            <th class="text-center" width="120">Comentario del Maestro</th>
                            <?php if ((int)$this->user_type !== 3): ?>
                            <th class="text-center" width="120">Motivo</th>
                            <th class="text-center" width="90">Fecha de Contacto</th>
                            <th class="text-center" width="90">Fecha de Regreso</th>
                            <?php endif ?>
                            <th class="text-center" width="80">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->absences as $index => $falta): ?>
                            <tr>
                                <td><?= ($index + 1) ?></td>
                                <td><?= $falta->student; ?></td>
                                <td><?= date('d/m/Y',strtotime($falta->absence_date)); ?></td>
                                <td><?= ucwords(strtolower($falta->course)); ?></td>
                                <td><?= ucwords(strtolower($falta->teacher)); ?></td>
                                <td><?= $falta->teacher_note; ?></td>
                                <?php if ((int)$this->user_type !== 3): ?>
                                <td><?= $falta->absence_note; ?></td>
                                <td><?= $falta->contact_date; ?></td>
                                <td><?= $falta->return_date; ?></td>
                                <?php endif ?>
                                <td>

                                    <button class="btn btn-sm btn-info edit_absence"
                                            data-absence="<?= $falta->absence_id; ?>"
                                            data-student="<?= $falta->student; ?>"
                                            data-teacher="<?= $falta->teacher_id; ?>"
                                            data-teachernote="<?= $falta->teacher_note; ?>"
                                            data-absencenote="<?= $falta->absence_note; ?>"
                                            data-absencedate="<?= $falta->absence_date; ?>"
                                            data-contactdate="<?= $falta->contact_date; ?>"
                                            data-returndate="<?= $falta->return_date; ?>"
                                            ><i class="fas fa-edit"></i></button>
                                    
                                    <button class="btn btn-sm btn-danger delete_absence" 
                                            data-absence="<?= $falta->absence_id; ?>"
                                            data-student="<?= $falta->student; ?>"
                                            ><i class="fas fa-trash"></i></button>
                                   
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <h5 class="text-info text-center">Lista Vacia</h4>
                <?php endif ?>
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
                                <option value="" hidden>Seleccione alumno...</option>
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
                            <?php if ((int)$this->user_type !== 3): ?>
                            <label for="inputname">Maestro: </label>
                            <select class="form-control form-control-sm" name="maestro" id="maestro" style="width: 100%;" required="true">
                                <option>Seleccione maestro...</option>
                                <?php foreach ($this->maestros as $index => $maestro): ?>
                                    <option value="<?= $maestro->user_id; ?>"><?= $maestro->name; ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php else: ?>
                                <input type="hidden" name="maestro" value="<?= (int)$this->current; ?>">
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="comentario_maestro" class="control-label">Comentario del Maestro: </label>
                             <textarea name="comentario_maestro" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <?php if ((int)$this->user_type !== 3): ?>
                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="motivo_falta" class="control-label">Motivo de Falta: </label>
                            <textarea name="motivo_falta" class="form-control" rows="3" required></textarea>
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
                    <?php endif ?>
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

<div class="modal fade" id="edit_register_modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title mb-0 text-white" id="ModalCenterTitle">Editar Registro de Inasistencia</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmUpdateRegister" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-8 px-2">
                        <div class="form-group">
                            <label for="inputname">Alumno:<br> <strong id="edit_student_name"></strong></label>
                            <input type="hidden" id="edit_student" name="alumno" value="">
                            <input type="hidden" id="edit_absenceId" name="inasistencia" value="">
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 px-2">
                        <div class="form-group">
                            <label for="fecha_falta">Fecha de Inasistencia: </label>
                            <input type="text" 
                                   id="edit_absence_date" 
                                   class="form-control form-control-sm"
                                   placeholder="Inicia.." 
                                   name="fecha_falta"
                                   autocomplete="off" 
                                   required>
                        </div>
                    </div>

                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <?php if ((int)$this->user_type !== 3): ?>
                            <label for="inputname">Maestro: </label>
                            <select class="form-control form-control-sm"  name="maestro" id="edit_teacher" style="width: 100%;" required="true">
                                <option>Seleccione maestro...</option>
                                <?php foreach ($this->maestros as $index => $maestro): ?>
                                    <option value="<?= $maestro->user_id; ?>"><?= $maestro->name; ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php else: ?>
                                <input type="hidden" name="maestro" value="<?= (int)$this->current; ?>">
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="comentario_maestro" class="control-label">Comentario del Maestro: </label>
                             <textarea name="comentario_maestro" id="edit_teacher_note" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <?php if ((int)$this->user_type !== 3): ?>
                    <div class="col-sm-12 px-2">
                        <div class="form-group">
                            <label for="motivo_falta" class="control-label">Motivo de Falta: </label>
                            <textarea name="motivo_falta" id="edit_absence_note" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 px-2">
                        <div class="form-group mb-0">
                            <label for="fecha_contacto" class="control-label">Fecha de Contacto: </label>
                            <input type="text" 
                                   id="edit_contact_date" 
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
                                       id="edit_return_date" 
                                       class="form-control form-control-sm"
                                       placeholder="Finaliza.." 
                                       name="fecha_regreso"
                                       autocomplete="off">
                        </div>
                    </div>
                    <?php endif ?>
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

<div class="modal fade" id="delete_register_modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-danger">
                <h6 class="modal-title mb-0 text-white" id="ModalCenterTitle">Eliminar Registro de Inasistencia</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmDeleteRegister" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-10 px-2 text-center">
                        <div class="form-group">
                            <label for="inputname">Alumno: <strong id="delete_student_name"></strong></label>
                            <input type="hidden" id="delete_absenceId" name="inasistencia" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2 py-2 px-2">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat-lg" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" class="btn btn-danger btn-sm btn-flat-lg">Eliminar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>