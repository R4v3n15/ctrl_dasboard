<div class="row" id="page-content-wrapper">
    <main role="main" class="col-12 col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Pagos Agosto-Diciembre <?= date('Y'); ?></h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <?php if ($this->cursos): ?>
                        <?php foreach ($this->cursos as $curso): ?>
                            <button class="btn btn-sm btn-outline-primary pays_view" 
                                    id="tabla_<?= $curso->course_id; ?>" 
                                    data-table="<?= $curso->course_id; ?>">
                                <?= $curso->course; ?> 
                                <span class="badge badge-secondary" id="count_<?= $curso->course_id; ?>">0</span>
                            </button>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 px-0">
                <div class="table-responsive">
                    <table id="tabla_pagos" class="table table-sm table-striped" style="width:100%">
                        <thead>
                            <tr class="info">
                                <th class="text-center" width="10"> N° </th>
                                <th class="text-center" width="80">Alumno</th>
                                <th class="text-center" width="100">Tutor</th>
                                <th class="text-center" width="12">Ago</th>
                                <th class="text-center" width="12">Sep</th>
                                <th class="text-center" width="12">Oct</th>
                                <th class="text-center" width="12">Nov</th>
                                <th class="text-center" width="12">Dic</th>
                                <th class="text-center" width="190">Comentario</th>
                                <th class="text-center" width="15">Opción</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr class="info">
                                <th class="text-center"> N° </th>
                                <th class="text-center">Alumno</th>
                                <th class="text-center">Tutor</th>
                                <th class="text-center">Ago</th>
                                <th class="text-center">Sep</th>
                                <th class="text-center">Oct</th>
                                <th class="text-center">Nov</th>
                                <th class="text-center">Dic</th>
                                <th class="text-center">Comentario</th>
                                <th class="text-center">Opción</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>        
    </main>
</div>


<!-- M O D A L -->
<div class="modal fade" id="modalPayMonth" tabindex="-1" role="dialog" aria-labelledby="ModalPayTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-success">
                <h6 class="modal-title my-0 text-white" id="ModalPayTitle">MENSUALIDAD DE <span id="month_name"></span></h6>
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
                        <h6 class="text-center text-danger mb-0" id="response"></h6>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary btn-shadown" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="toggle_pay" class="btn btn-primary btn-shadown">Guardar</button>
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
                    <div class="col-12 col-md-8">
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
                                <option value="" hidden>Seleccione..</option>
                                <option value="1">Pagar</option>
                                <option value="3">No Aplica</option>
                                <option value="0">Adeudo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12"">
                        <textarea class="form-control" 
                                  rows="3" 
                                  id="payComment" 
                                  name="payComment"
                                  placeholder="Escriba aquí su comentario.."></textarea>
                    </div>
                    <div class="col-12 col-md-12 mb-2">
                        <h6 class="text-center text-success mb-0" id="responseAction"></h6>
                    </div>
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-secondary btn-shadown" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-6 text-center">
                        <button type="submit" class="btn btn-primary btn-shadown">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddComment" tabindex="-1" role="dialog" aria-labelledby="commentTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-primary">
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
            <div class="row mb-3">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary btn-shadown" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="save_comment" class="btn btn-primary btn-shadown">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>