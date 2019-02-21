<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Alumnos Becados</h5>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <?php if (count($this->becados) > 0): ?>
                <div class="table-responsive">
                    <table id="tabla_becados" class="table table-sm table-striped" style="width:100%">
                        <thead>
                            <tr class="info">
                                <th width="50" class="text-center"> N° </th>
                                <th width="100" class="text-center">Alumno</th>
                                <th width="80" class="text-center">Teléfono 1</th>
                                <th width="80" class="text-center">Teléfono 2</th>
                                <th width="100" class="text-center">Grupo</th>
                                <th width="100" class="text-center">Horario</th>
                                <th width="100" class="text-center">Maestro</th>
                                <th width="100" class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->becados as $key => $becado): ?> 
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?= $becado->name; ?></td>
                                <td><?= $becado->cellphone; ?></td>
                                <td>
                                    <?php if ($becado->tutor !== null): ?>
                                        <?php if ($becado->tutor->relationship != '' && $becado->tutor->cellphone != ''): ?>
                                            <?= $becado->tutor->cellphone.' ('. $becado->tutor->relationship .')'; ?>
                                        <?php endif ?>
                                        
                                        <?php if ($becado->tutor->phone != ''): ?>
                                            <br>
                                            <?= $becado->tutor->phone.' ('. $becado->tutor->relationship .')'; ?>
                                        <?php endif ?>
                                        <?php if ($becado->tutor->relationship_alt != '' && $becado->tutor->phone_alt != ''): ?>
                                            <br>
                                            <?= $becado->tutor->phone_alt.' ('. $becado->tutor->relationship_alt .')'; ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                </td>
                                <td><?= $becado->clase; ?></td>
                                <td><?= $becado->dias; ?> <br> <?= $becado->horario; ?></td>
                                <td><?= $becado->maestro; ?></td>
                                <td>
                                    <!-- <button type="button" 
                                            class="btn btn-sm btn-info mr-3 detail_scholar"
                                            data-scholar="<?= $becado->beca_id; ?>"
                                            data-name="<?= $becado->name; ?>"><i class="fa fa-cog"></i></button> -->
                                    <button type="button" 
                                            class="btn btn-sm btn-danger remove_scholar"
                                            data-scholar="<?= $becado->beca_id; ?>"
                                            data-name="<?= $becado->name; ?>"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <h5 class="text-center text-info">No hay alumnos becados</h5>
                <?php endif ?>
            </div>
        </div>
    </main>
</div>


<div class="modal fade" id="remove_scholar_modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-danger">
                <h6 class="modal-title text-white my-0" id="scholar_title">Eliminar alumno como becario</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frmDeleteScholar">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center text-info">¿Eliminar alumno <strong id="scholar_name"></strong> como becario?</p>
                        <input type="hidden" id="scholar_idStudent" name="idStudent">
                    </div>
                </div>
            </div>
            <div class="modal-footer mb-1 py-2 px-2">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-sm btn-secondary btn-flat-lg" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" id="scholar_submit" class="btn btn-sm btn-danger btn-flat-lg">Eliminar</button>
                </div>
                
            </div>
            </form>
        </div>
    </div>
</div>

