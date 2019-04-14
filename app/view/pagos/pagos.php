<style type="text/css" media="screen">
    table>tbody>tr>td {
        color: #555;
        font-size: 13px;
    }
</style>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-12 col-md-12 px-4">
        <div class="align-items-center pt-0">
            <div class="mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <?php if ($this->cursos): ?>
                        <?php foreach ($this->cursos as $index => $curso): ?>
                            <button class="btn btn-sm btn-outline-secondary change_costs" 
                                    data-course="<?= $curso->course_id; ?>"
                                    data-name="<?= ucwords(strtolower($curso->course)); ?>"
                                    data-normal="<?= $curso->costo_normal; ?>"
                                    data-promo="<?= $curso->costo_descuento; ?>"
                                    style="font-size: 0.7rem;">
                                <?= ucwords(strtolower($curso->course)); ?>
                                <hr class="my-1">
                                <?= 'Costo Normal: '. $curso->costo_normal; ?>
                                <br>
                                <?= 'Semana Desc.: '. $curso->costo_descuento; ?>
                            </button>
                        <?php endforeach ?>
                    <?php endif ?>
                    <button class="btn btn-sm btn-outline-info px-5">
                        <h5 class="">Pagos - <?= date('Y'); ?></h5>
                    </button>
                </div>
            </div>
        </div>
        <div class="align-items-center pt-2 pb-2 mb-3 border-bottom">
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <?php if ($this->cursos): ?>
                        <?php foreach ($this->cursos as $index => $curso): ?>
                            <button class="btn btn-sm btn-outline-primary pays_view px-3" 
                                    id="tabla_<?= $curso->course_id; ?>" 
                                    data-table="<?= $curso->course_id; ?>">
                                    <?= ucwords(strtolower($curso->course)); ?> 
                                <span class="badge badge-dark" id="count_<?= $curso->course_id; ?>">0</span>
                            </button>
                        <?php endforeach ?>
                    <?php endif ?>
                    <button class="btn btn-sm btn-outline-primary pays_view px-4" id="tabla_all" data-table="all">
                        <strong>TODOS</strong>
                        <span class="badge badge-dark" id="count_all">0</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 px-0">
                <div class="table-responsive">
                    <table id="tabla_pagos_completo" class="table table-sm table-striped" style="width:100%">
                        <thead>
                            <tr class="info">
                                <th class="text-center">#</th>
                                <th class="text-center">Alumno</th>
                                <th class="text-center">Estado</th>
                                <!-- <th class="text-center">Tutor</th> -->
                                <th class="text-center">Grupo</th>
                                <th class="text-center">Ene</th>
                                <th class="text-center">Feb</th>
                                <th class="text-center">Mar</th>
                                <th class="text-center">Abr</th>
                                <th class="text-center">May</th>
                                <th class="text-center">Jun</th>
                                <th class="text-center">Ago</th>
                                <th class="text-center">Sep</th>
                                <th class="text-center">Oct</th>
                                <th class="text-center">Nov</th>
                                <th class="text-center">Dic</th>
                                <th class="text-center">Comentario</th>
                                <th class="text-center">Pagar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>        
    </main>
</div>

<!-- M O D A L -->
<div class="modal fade" id="modalPayMonth" tabindex="-1" role="dialog" aria-labelledby="ModalPayTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title my-0 text-white" id="ModalPayTitle">MENSUALIDAD DE <span id="month_name"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center pb-0">
                    <div class="col-12">
                        <p class="text-center">Alumno: <strong id="student_name"></strong></p>
                        <input type="hidden" id="student_id" class="form-control">
                        <input type="hidden" id="month_to_pay" class="form-control">
                        <div class="form-group">
                            <select class="form-control form-control-sm" id="pay_action">
                                <option value="" hidden>Seleccione..</option>
                                <option value="1">Pagar</option>
                                <option value="3">No Aplica</option>
                                <option value="0">Adeudo</option>
                            </select>
                        </div>
                        <h6 class="text-center text-danger mb-0" id="response"></h6>
                    </div>
                </div>
            </div>
            <div class="row mb-3 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-shadown" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="button" id="toggle_pay" class="btn btn-info btn-sm btn-shadown">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPayAction" tabindex="-1" role="dialog" aria-labelledby="ModalPayTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title my-0 text-white" id="ModalPayTitle">PAGAR MENSUALIDAD</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payForm" class="row justify-content-center pb-0">
                    <div class="col-12">
                        <p class="text-center">Alumno: <strong id="nameStudent"></strong></p>
                        <p class="text-center">Familiares: <br> <strong id="relativesStudent"></strong></p>
                        <input type="hidden" id="payStudent" name="payStudent" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="monthToPay" id="monthToPay">
                                
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="payStatus" id="payStatus">
                                <option value="" hidden>Seleccione acción..</option>
                                <option value="1">Pagar</option>
                                <option value="3">No Aplica</option>
                                <option value="0">Adeudo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <textarea class="form-control" 
                                  rows="3" 
                                  id="payComment" 
                                  name="payComment"
                                  placeholder="Escriba aquí su comentario.."></textarea>
                    </div>
                    <div class="col-12 col-md-12 mb-2">
                        <h6 class="text-center text-success mb-0" id="responseAction"></h6>
                    </div>
                    <div class="col-6 text-left">
                        <button type="button" class="btn btn-secondary btn-sm btn-shadown" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-info btn-sm btn-shadown">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddComment" tabindex="-1" role="dialog" aria-labelledby="commentTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title my-0 text-white" id="commentTitle">AGREGAR COMENTARIO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <input type="hidden" id="id_alumno" class="form-control">
                        <div class="form-group row">
                            <div class="col-12">
                                <textarea class="form-control" 
                                          rows="5" 
                                          id="comment" 
                                          name="comment"
                                          placeholder="Escriba aquí su comentario.."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-shadown" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="button" id="save_comment" class="btn btn-info btn-sm btn-shadown">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddStatus" tabindex="-1" role="dialog" aria-labelledby="commentTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title my-0 text-white" id="commentTitle">AGREGAR ESTADO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmSaveStatus">
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <input type="hidden" id="status_idStudent" name="student_id" class="form-control">
                        <div class="form-group row">
                            <div class="col-12">
                                <textarea class="form-control" 
                                          rows="3" 
                                          id="status" 
                                          name="status"
                                          placeholder="Agregue aquí la información"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-shadown" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" class="btn btn-info btn-sm btn-shadown">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalChangeCourseCost" tabindex="-1" role="dialog" aria-labelledby="commentTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-success">
                <h6 class="modal-title my-0 text-white" id="commentTitle">Cambiar Precios de <span id="course_name"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmUpdateCourseCosts">
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <input type="hidden" id="update_idCourse" name="course_id" class="form-control">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Costo Normal:</span>
                            </div>
                            <input type="text" name="costo_normal" id="edit_normal_cost" class="form-control" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">MXN</span>
                            </div>
                        </div>
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Costo Descuento:</span>
                            </div>
                            <input type="text" name="costo_descuento" id="edit_promo_cost" class="form-control" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">MXN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-shadown btn-flat-lg" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" class="btn btn-success btn-sm btn-shadown">Guardar Cambios</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>



<!-- <div class="modal fade" id="modalPayMonth" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Mensualidad: <span id="month_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center pb-0">
                    <div class="col-12 col-md-8">
                        <p class="text-center">Alumno: <strong id="student_name"></strong></p>
                        <input type="hidden" id="student_id" class="form-control">
                        <input type="hidden" id="month_to_pay" class="form-control">
                        <div class="form-group">
                            <select class="form-control form-control-sm" id="pay_action">
                                <option value="" hidden>Seleccione..</option>
                                <option value="1">Pagar</option>
                                <option value="3">No Aplica</option>
                                <option value="0">Adeudo</option>
                            </select>
                        </div>
                        <h6 class="text-center text-success mb-0" id="response"></h6>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="toggle_pay" class="btn btn-sm btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddComment" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentTitle">Agregar Comentario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <input type="hidden" id="id_alumno" class="form-control">
                        <div class="form-group row">
                            <label class="col-12 text-center">Escriba su Comentario:</label>
                            <div class="col-12">
                                <textarea class="form-control" rows="3" id="comment" name="comment"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="save_comment" class="btn btn-sm btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="template" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        .....
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="add_teacher" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div> -->