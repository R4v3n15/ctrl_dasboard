<?php 
    $alumno = $this->alumno; 
    $url_base = Config::get('URL');
?>
<div class="row" id="page-content-wrapper" style="margin-left: 0;">
    <main role="main" class="col-md-12 pr-3 mb-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Perfil</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="<?= $url_base; ?>alumnos" 
                       class="btn btn-sm btn-outline-secondary btn-flat-md"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="Volver a Alumnos"><i class="fa fa-arrow-left"></i></a>
                    <button class="btn btn-sm btn-outline-info editar-datos"
                            id="form-1"
                            data-form="1">Alumno</button>
                    <button class="btn btn-sm btn-outline-info editar-datos btn-flat-sm"
                            id="form-2"
                            data-form="2">Tutor</button>
                    <button class="btn btn-sm btn-outline-info editar-datos"
                            id="form-3" 
                            data-form="3">Academicos</button>
                    <a  class="btn btn-sm btn-outline-secondary"
                        href="<?= $url_base; ?>mapa/u/<?= $alumno->student_id; ?>"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Editar Croquis">Croquis</a>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-md-3">
                <div class="card px-0 box-shadow">
                    <div class="card-img my-2">
                            <img class="rounded rounded-circle img-fluid"
                                 id="changeAvatar"
                                 data-alumno="<?= $alumno->student_id; ?>"
                                 src="<?= $alumno->avatar;?>"
                                 alt="avatar">
                    </div>
                    <div class="card-title mt-1 pb-0 mb-0">
                        <h5 class="text-center text-info" 
                            id="alumno" 
                            data-alumno="<?= $alumno->student_id; ?>"
                            data-tutor="<?= $alumno->id_tutor; ?>"
                            data-clase="<?= $alumno->class_id; ?>">
                            <?= $alumno->name; ?>
                        </h5>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item py-2">
                                <b>Grupo:</b>
                                <?php if ($alumno->grupo !== null): ?>
                                    <?= $alumno->grupo;  ?>
                                <?php else: ?>
                                    <a class="link adding_group"
                                       href="javascript:void(0)"
                                       data-student="" 
                                       data-toggle="modal" 
                                       data-target="#add_to_group"  
                                       title="Agregar grupo">Agregar a Grupo</a>
                                <?php endif ?>
                            </li>
                            <li class="list-group-item py-2"><b>Edad:</b> <?= $alumno->age; ?> Años</li>
                            <li class="list-group-item py-2"><b>Tel. Celular:</b> <?= $alumno->cellphone; ?></li>
                            <?php if ($alumno->tutor !== null): ?>
                            <li class="list-group-item py-2"><b>Tutor: </b><?= $alumno->tutor->name; ?></li>
                            <li class="list-group-item py-2"><b>Tel. de Casa: </b><?= $alumno->tutor->phone; ?></li>
                            <li class="list-group-item py-2"><b>Tel. Celular: </b><?= $alumno->tutor->cellphone; ?></li>
                            <li class="list-group-item py-2"><b>Tel. Alterno: </b><?= $alumno->tutor->phone_alt; ?></li>
                            <?php endif ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <label><strong>Fecha de Inscripción:</strong><br> <?= $alumno->created_at; ?> </label>
                    </div>
                </div>
            </div>
            <div class="col-md-9" id="editar_form">
                
            </div>
        </div>
    </main>
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

<div id="checkout" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-delete">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Dar de Baja</h4>
            </div>
            <div class="modal-body">
                <p class="text-center"> Dara de baja a:<br> <strong id="student_name"></strong>. <br> 
                                        ¿Desea continuar con esta acción?
                </p>
                <input type="hidden" id="alumno_id">
            </div>
            <div class="row">
                <div class="modal-footer col-sm-10 col-sm-offset-1 text-center">
                    <button type="button" id="no_checkout" class="btn btn-sm btn-gray btn-raised left" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="checkout_student" class="btn btn-sm btn-danger btn-raised right">Dar de Baja</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="checkin" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Dar de Alta</h4>
            </div>
            <div class="modal-body">
                <p class="text-center"> Dara de alta a:<br> <strong id="alumno_name"></strong>. <br> 
                                        ¿Desea continuar con esta acción?
                </p>
                <input type="hidden" id="id_alumno">
            </div>
            <div class="row">
                <div class="modal-footer col-sm-10 col-sm-offset-1 text-center">
                    <button type="button" id="no_checkin" class="btn btn-sm btn-gray btn-raised left" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="checkin_student" class="btn btn-sm btn-second btn-raised right">Dar de Alta</button>
                </div>
            </div>
        </div>
    </div>
</div>
