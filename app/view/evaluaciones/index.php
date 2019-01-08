<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Evaluaciones</h5>
            <h5 class="text-center"><?= $this->student_name; ?></h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="<?= _root(); ?>evaluaciones/ev/<?= $this->student_id; ?>" 
                       class="btn btn-sm btn-outline-info">Evaluar</a>
                </div>
            </div>
        </div>
        <?php $user_type = (int)Session::get('user_type'); ?>

        <?php if ($user_type === 777): ?>
        <div class="row">
            <div class="col-md-12">
                <?php $this->renderFeedbackMessages(); ?>
            </div>
            <div class="col-md-6">
                <div class="card card-success box-shadow">
                    <div class="card-header text-white" style="height: auto;">
                        <h5 class="text-center">Enero-Febrero</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-center"><strong>Tema:</strong> The Numbers</h6>
                        <table class="table table-bordered table-hover table-striped table-slim">
                            <thead>
                                <tr class="bg-green">
                                    <th>Achievement</th>
                                    <th>Excellent</th>
                                    <th>Good</th>
                                    <th>Average</th>
                                    <th>Weak</th>
                                    <th class="d-none">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reading</td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="reading" class="form-control form-control-sm" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Writing</td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="writing" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Speaking</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="speaking" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Listening</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="listening" class="form-control form-control-sm" value="0"></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-hover table-striped table-slim">
                            <thead>
                                <tr class="bg-green">
                                    <th>Effort</th>
                                    <th>Excellent</th>
                                    <th>Good</th>
                                    <th>Average)</th>
                                    <th>Weak</th>
                                    <th class="d-none">Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reading</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="read" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Writing</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="write" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Speaking</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="speak" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Listening</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="listen" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Participation.</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="attitude" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Teamwork.</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="team" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Homeworks</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="d-none">
                                        <input type="text" id="time" class="form-control form-control-sm" value="0"></td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="text-center p-0 m-0"><strong>Fecha de Evaluación:</strong> 20 FEB 2017</h6>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-secondary box-shadown float-right">Vista Detallada</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </main>
</div>