<?php $alumno = $this->alumno; ?>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Perfil</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-secondary">Tutor</button>
                    <button class="btn btn-sm btn-outline-secondary">Alumno</button>
                    <button class="btn btn-sm btn-outline-secondary">Academicos</button>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-3">
                <div class="card px-0">
                    <img class="card-img-top px-5" src="<?= $alumno->avatar;?>" alt="avatar" width="100" height="120">
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
            <div class="col-sm-9" id="editar_form">
                formulario
            </div>
        </div>
    </main>
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
