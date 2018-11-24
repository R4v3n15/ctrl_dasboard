<?php $base_url = Config::get('URL'); ?>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Evaluaciones</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="<?= $base_url; ?>evaluaciones/evaluar/<?= $this->alumno; ?>" 
                       class="btn btn-sm btn-outline-info">Evaluar</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="mb-0"><i class="fa fa-cogs"></i></h3>
                <h5 class="text-info mb-4">
                    | - - - - - - - - - - - - - - - - -  - - - - - - - - - |<br>
                     E N &nbsp;&nbsp;&nbsp; M A N T E N I M I E N T O <br>
                    | - - - - - - - - - - - - - - - - -  - - - - - - - - - |
                </h5>
                <img src="<?= Config::get('URL');?>public/assets/img/loading.gif">
            </div>
        </div>

        <?php $user_type = (int)Session::get('user_type'); ?>

        <?php if ($user_type === 777): ?>
        <div class="row">
            <div class="col-md-12">
                <?php $this->renderFeedbackMessages(); ?>
            </div>
            <div class="col-md-6">
                <div class="well card">
                    <div class="card-title">
                        <h4 class="text-center">Periodo Enero-Febrero</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-center"><strong>Tema:</strong> The Numbers</h5>
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr class="tb_purple">
                                    <th>Achievement</th>
                                    <th>Excellent</th>
                                    <th>Good</th>
                                    <th>Average</th>
                                    <th>Weak</th>
                                    <th class="hidden">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reading</td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="reading" class="form-control form-control-sm" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Writing</td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="writing" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Speaking</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="speaking" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Listening</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="listening" class="form-control form-control-sm" value="0"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr class="tb_purple">
                                    <th>Effort</th>
                                    <th>Excellent</th>
                                    <th>Good</th>
                                    <th>Average)</th>
                                    <th>Weak</th>
                                    <th class="hidden">Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reading</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="read" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Writing</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="write" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Speaking</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="speak" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Listening</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="listen" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Participation.</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="attitude" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Teamwork.</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="team" class="form-control form-control-sm" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Homeworks</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden">
                                        <input type="text" id="time" class="form-control form-control-sm" value="0"></td>
                                </tr>
                            </tbody>
                        </table>
                        <h5 class="text-center"><strong>Fecha de Evaluación:</strong> 20 FEB 2017</h5>
                    </div>
                    <div class="card-footer">
                        <label> Vista Detallada</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="well card">
                    <div class="card-title">
                        <h4 class="text-center">Periodo Marzo-Abril</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-center"><strong>Tema:</strong> The Week Days</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque sit alias iste quisquam porro odio accusantium placeat libero itaque eius, odit molestias corporis pariatur ullam quibusdam harum nulla impedit labore.</p>
                        <h5 class="text-center"><strong>Fecha de Evaluación:</strong> 20 ABR 2017</h5>
                    </div>
                    <div class="card-footer">
                        <label> Vista Detallada</label>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </main>
</div>