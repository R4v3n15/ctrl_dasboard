<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Reporte: Registro de  Alumnos</h5>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <h6 class="text-center text-primary">Resumen</h6>
                <table id="table" class="table table-sm table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th width="60%" class="text-center text-secondary">Alumnos con Grupo: </th>
                            <td width="40%" class="text-center"><?= $this->alumnosActivos; ?></td>
                        </tr>
                        <tr>
                            <th width="60%" class="text-center text-secondary">Alumnos sin Grupo: </th>
                            <td width="40%" class="text-center"><?= $this->whitoutGroup; ?></td>
                        </tr>
                        <tr>
                            <th width="60%" class="text-center text-secondary">Alumnos en Espera: </th>
                            <td width="40%" class="text-center"><?= $this->waiting; ?></td>
                        </tr>
                        <tr>
                            <th width="60%" class="text-center text-secondary">Alumnos de Baja: </th>
                            <td width="40%" class="text-center"><?= $this->standby; ?></td>
                        </tr>
                        <tr>
                            <th width="60%" class="text-center text-secondary">Alumnos Egresados: </th>
                            <td width="40%" class="text-center"><?= $this->egresados; ?></td>
                        </tr>
                        <tr>
                            <th width="60%" class="text-center text-secondary">Alumnos Eliminados: </th>
                            <td width="40%" class="text-center"><?= $this->deleted; ?></td>
                        </tr>
                        <tr class="bg-secondary">
                            <th width="60%" class="text-center text-white">Total Alumnos: </th>
                            <th width="40%" class="text-center text-white"><?= $this->totalAlumnos; ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row justify-content-center">
            <?php foreach ($this->courses as $curso): ?>
            <div class="col-12 col-md-6">
                <h6 class="text-center text-secondary"><?= $curso->course; ?></h6>
                <table id="table" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr class="bg-success">
                            <th class="text-center"></th>
                            <th class="text-center text-white">Grupo</th>
                            <th class="text-center text-white">Alumnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($curso->grupos as $index => $grupo): ?>
                            <?php $total += $grupo->alumnos; ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td>
                                    <?= $curso->name . " " . $grupo->group_name; ?>  
                                </td>
                                <td class="text-center">
                                    <?= $grupo->alumnos; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <tr>
                            <th></th>
                            <th class="text-right">TOTAL: </th>
                            <th class="text-center"><?= $total; ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php endforeach ?>
        </div>
    </main>
</div>