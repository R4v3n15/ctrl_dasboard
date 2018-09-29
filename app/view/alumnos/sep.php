<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-danger">Fuera de Servicio</h5>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <h4 class="text-info my-2">
                    C A R D S
                </h4>
            </div>
            
            <div class="col-md-10 text-center">
                <div class="row">
                <?php if ($this->students): ?>
                    <?php $cont = 1; foreach ($this->students as $student): ?>
                        <?php $clase = 'info'; if ($cont % 2 == 0): $clase = 'success'; ?><?php endif ?>
                        <div class="col-3">
                            <div class="card card-<?= $clase; ?>">
                                <div class="card-header"></div>
                                <div class="card-avatar mx-auto">
                                    <img src="<?= _avatar(); ?>default.jpg" alt="avatar">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title text-center"><?= $student->name; ?> <?= $cont; ?></h6>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item pt-2 pb-1"><strong>Grupo: </strong><?= $student->class_id; ?></li>
                                        <li class="list-group-item pt-2 pb-1"><strong>Edad: </strong><?= $student->age; ?></li>
                                        <li class="list-group-item pt-2 pb-1"><strong>Sexo: </strong><?= $student->genre; ?></li>
                                        <li class="list-group-item pt-2 pb-1"><strong>Tel. </strong><?= $student->cellphone; ?></li>
                                        <li class="list-group-item pt-2 pb-1"><strong>Tutor:</strong><?= $student->id_tutor; ?></li>
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <a href="#" class="btn btn-primary">Agregar</a>
                                </div>
                            </div>
                        </div>
                    <?php $cont++; endforeach ?>
                <?php endif ?>
                </div>
            </div>

        </div>
    </main>
</div>