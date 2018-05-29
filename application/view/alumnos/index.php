<?php $base_url = Config::get('URL'); ?>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Alumnos</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <?php if ($this->cursos): ?>
                        <?php foreach ($this->cursos as $curso): ?>
                            <button class="btn btn-sm btn-outline-primary students_view" 
                                    id="curso_<?= $curso->course_id; ?>" 
                                    data-curso="<?= $curso->course_id; ?>">
                                <?= $curso->course; ?> 
                                <span class="badge badge-secondary" id="count_<?= $curso->course_id; ?>">0</span>
                            </button>
                        <?php endforeach ?>
                    <?php endif ?>
                    <?php if ($this->u_type === '1' || $this->u_type === '2'): ?>
                    <button class="btn btn-sm btn-outline-secondary students_view" id="curso_standby" data-curso="standby">
                        EN ESPERA
                        <span class="badge badge-dark" id="count_standby">0</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary students_view" id="curso_all" data-curso="all">
                        TODOS
                        <span class="badge badge-dark" id="count_all">0</span>
                    </button>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php $this->renderFeedbackMessages(); ?>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Check this custom checkbox</label>
                </div>
            </div>
            <div class="col-12" id="tabla_alumnos">
                
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAddToGroup" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddTitle">Agregar Alumno a Grupo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10">
                        <h6 class="text-center text-info" id="extra_message"></h6>
                        <h5 class="text-center text-secondary">
                            <small>Seleccione un curso de la lista, luego el grupo.</small>
                        </h5>

                        <input type="hidden" id="alumno_id" class="form-control">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon1">Curso:</span>
                                </div>
                                <select class="form-control" id="course" aria-label="Curso" aria-describedby="addon1">
                                    <option value="" hidden>Seleccione...</option>
                                    <?php if ($this->cursos): ?>
                                        <?php foreach ($this->cursos as $curso): ?>
                                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon12">Grupo:</span>
                                </div>
                                <select class="form-control" id="groups" aria-label="Curso" aria-describedby="addon2">
                                    <option value="" hidden>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="add_in_group" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="add_to_group" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Agregar Alumno a Grupo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <p class="text-center"><small>Seleccione un curso de la lista, luego el grupo.</small></p>
                        <input type="hidden" id="alumno_id" class="form-control">
                        <div class="form-group">
                            <label class="col-sm-6"><small>Curso:</small> 
                                <select class="form-control " id="course">
                                    <option value="">Seleccione...</option>
                                    <?php if ($this->cursos): ?>
                                        <?php foreach ($this->cursos as $curso): ?>
                                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </label>
                            <label class="col-sm-6"><small>Grupo:</small> 
                                <select class="form-control" id="groups">
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10 col-sm-offset-1 text-center">
                        <button type="button" id="add_in_group" class="btn btn-sm btn-second btn-raised">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="modalChangeGroup" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Cambiar de Grupo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <h6 class="text-center text-info">Seleccione un curso y grupo a asignar.</h6>
                        <input type="hidden" id="alumno_number" class="form-control">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon1">Curso:</span>
                                </div>
                                <select class="form-control" id="course_list" aria-label="Curso" aria-describedby="addon1">
                                    <option value="" hidden>Seleccione...</option>
                                    <?php if ($this->cursos): ?>
                                        <?php foreach ($this->cursos as $curso): ?>
                                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon12">Grupo:</span>
                                </div>
                                <select class="form-control" id="grupos" aria-label="Curso" aria-describedby="addon2">
                                    <option value="" hidden>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-sm-6"><small>Curso:</small> 
                                <select class="form-control " id="course_list">
                                    <option value="">Seleccione...</option>
                                    <?php if ($this->cursos): ?>
                                        <?php foreach ($this->cursos as $curso): ?>
                                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                    <option value="0">EN ESPERA</option>
                                </select>
                            </label>
                            <label class="col-sm-6"><small>Grupo:</small> 
                                <select class="form-control" id="grupos">
                                </select>
                            </label>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="change_group" class="btn btn-primary">Cambiar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="change_group" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Cambiar de Grupo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <h6 class="text-center"><small>Seleccione un curso y grupo a asignar.</small></h6>
                        <input type="hidden" id="alumno_number" class="form-control">
                        <div class="form-group">
                            <label class="col-sm-6"><small>Curso:</small> 
                                <select class="form-control " id="course_list">
                                    <option value="">Seleccione...</option>
                                    <?php if ($this->cursos): ?>
                                        <?php foreach ($this->cursos as $curso): ?>
                                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                    <option value="0">EN ESPERA</option>
                                </select>
                            </label>
                            <label class="col-sm-6"><small>Grupo:</small> 
                                <select class="form-control" id="grupos">
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-10 col-sm-offset-1 text-center">
                        <button type="button" id="do_change_group" class="btn btn-sm btn-second btn-raised">Cambiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div id="invoice_list" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Facturación</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="invoice_students_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalDeleteStudent" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-delete">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Eliminar Alumno</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" class="form-control text-center" id="alumno_id" />
                            <p class="text-center text-info">
                                ¿Está seguro de querer eliminar a: <br> <strong id="alumno_name"></strong>?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="modal-footer col-md-10 col-md-offset-1 text-center">
                        <input type="button" 
                               data-dismiss="modal" 
                               class="btn btn-sm btn-raised btn-gray left" 
                               value="CANCELAR">
                        <input type="button"
                               id="btnConfirmDeleteStudent" 
                               class="btn btn-sm btn-raised btn-danger right" 
                               value="ELIMINAR">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalDeleteSelectedStudent" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-delete">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Eliminar Alumnos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" class="form-control text-center" id="alumno_id" />
                            <p class="text-center text-info">
                                ¿Está seguro de querer eliminar a los <strong id="selected_students"></strong> alumnos seleccionados?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="modal-footer col-md-10 col-md-offset-1 text-center">
                        <input type="button" 
                               data-dismiss="modal" 
                               class="btn btn-sm btn-raised btn-gray left" 
                               value="CANCELAR">
                        <input type="button" 
                               class="btn btn-sm btn-raised btn-danger right" 
                               id="btnConfirmDeleteStudents"
                               value="ELIMINAR">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>