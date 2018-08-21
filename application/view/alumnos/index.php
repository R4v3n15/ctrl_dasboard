<div class="row" id="page-content-wrapper" style="margin-left: 0;">
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
            </div>
            <div class="col-12 px-0">
                <div class="table-responsive">
                    <table id="table_students" class="table table-sm table-striped" style="width:100%">
                        <thead>
                            <tr class="info">
                                <th class="text-center"> N° </th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Apellidos</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Escolaridad</th>
                                <th class="text-center">Edad</th>
                                <th class="text-center">Grupo</th>
                                <th class="text-center">Tutor</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr class="info">
                                <th class="text-center"> N° </th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Apellidos</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Escolaridad</th>
                                <th class="text-center">Edad</th>
                                <th class="text-center">Grupo</th>
                                <th class="text-center">Tutor</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-12 px-0" id="tabla_alumnos">
                
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAddToGroup" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title mb-0 text-white" id="modalAddTitle">AGREGAR ALUMNO A GRUPO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10">
                        <h6 class="text-center text-info" id="extra_message"></h6>
                        <h6 class="text-center text-secondary">
                            <small>Seleccione un curso de la lista, luego el grupo.</small>
                        </h6>

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
                                    <option value="0">En Espera</option>
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
            <div class="row mb-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="addToGroup" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalChangeGroup" tabindex="-1" role="dialog" aria-labelledby="modalChgTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title mb-0 text-white" id="modalChgTitle">CAMBIAR DE GRUPO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <h6 class="text-center text-info" id="updateMessage"></h6>
                        <h6 class="text-center text-secondary"><small>Seleccione un curso y un grupo.</small></h6>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon1">Curso:</span>
                                </div>
                                <select class="form-control" id="updatecourse" aria-label="Curso" aria-describedby="addon1">
                                    <option value="" hidden>Seleccione...</option>
                                    <?php if ($this->cursos): ?>
                                        <?php foreach ($this->cursos as $curso): ?>
                                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                     <option value="0">En Espera</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon12">Grupo:</span>
                                </div>
                                <select class="form-control" id="updategroups" aria-label="Curso" aria-describedby="addon2">
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
                    <button type="button" id="updateGroup" class="btn btn-primary">Cambiar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUnsuscribeStudent" tabindex="-1" role="dialog" aria-labelledby="mdlTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unsuscribeTitle">Dar de Baja Alumno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="hidden" class="form-control text-center" id="unsuscribe_student" />
                                <h6 class="text-center">
                                    ¿Está seguro de querer dar de baja a: <br> 
                                    <strong class="text-info" id="unsuscribe_name"></strong>?
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary btn-flat-sm" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="unsuscribeStudent" class="btn btn-primary">Der de Baja</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUnsuscribeStudents" tabindex="-1" role="dialog" aria-labelledby="MdTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unsuscribeTitle">Dar de Baja Alumnos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="hidden" class="form-control text-center" id="unsuscribe_student" />
                                <h5 class="text-center" id="unsuscribe_message">
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary btn-flat-sm" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="unsuscribeStudents" class="btn btn-primary">Der de Baja</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteStudent" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitle">Eliminar Alumno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <input type="hidden" class="form-control text-center" id="delete_student" />
                        <h6 class="text-center text-secondary">¿Está seguro de querer eliminar a:</h6>
                        <h5 class="text-center text-info">
                            <small id="delete_name"></small>?
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="deleteStudent" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteStudents" tabindex="-1" role="dialog" aria-labelledby="reTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reTitle">Eliminar Alumnos Seleccionados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center text-info">
                            ¿Eliminar a los <strong id="delete_students"></strong> alumnos seleccionados?
                        </h6>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary btn-flat-sm" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="deleteStudents" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalInvoiceList" tabindex="-1" role="dialog" aria-labelledby="invoiceTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceTitle">Lista de Facturación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <table id="invoice_table" class="table table-sm table-striped" style="width:100%">
                            <thead>
                                <tr class="">
                                    <th class="text-center">Alumno</th>
                                    <th class="text-center">Num. Celular</th>
                                    <th class="text-center">Tutor</th>
                                    <th class="text-center">Tel. Casa</th>
                                    <th class="text-center">Tel. Celular</th>
                                    <th class="text-center">Tel. Alterno</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Alumno</th>
                                    <th class="text-center">Num. Celular</th>
                                    <th class="text-center">Tutor</th>
                                    <th class="text-center">Tel. Casa</th>
                                    <th class="text-center">Tel. Celular</th>
                                    <th class="text-center">Tel. Alterno</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalChangeAvatar" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitle">Cambiar Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmChangeAvatar" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avatar">Cambiar foto:</label>
                                <div class="col-sm-12">
                                    <input type="hidden" name="avatar_student" id="avatar_student" />
                                    <input type="file" id="avatar_file" name="avatar_file" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-6 text-center">
                            <button type="submit" id="deleteStudent" class="btn btn-info">Cambiar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

