<?php $base_url = Config::get('URL'); ?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?= $base_url; ?>dashboard">Inicio</a></li>
        <li><a href="<?= $base_url; ?>alumno">Alumnos</a></li>
        <li><a href="javascript:void(0)" class="active">Calificaciones</a></li>
        <a style="float: right;" href="javascript:void(0)" class="active">
            <strong>Alumno: </strong> <?= $this->alumno; ?>
        </a>
    </ol>    
    
    <div class="well">
        <?php $this->renderFeedbackMessages(); ?>
        <div class="row" id="evaluation_template">
            <div class="col-sm-12 text-center">
                <a href="<?= $base_url; ?>evaluaciones/evaluar/<?= $this->alumno; ?>" class="btn btn-primary btn-sm">
                    <i class="glyphicon glyphicon-check o-yellow"></i> Evaluar
                </a>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
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
                                    <td class="hidden"><input type="text" id="reading" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Writing</td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="writing" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Speaking</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="speaking" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Listening</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star checked"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="listening" class="form-control" value="0"></td>
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
                                    <td class="hidden"><input type="text" id="read" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Writing</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="write" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Speaking</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="speak" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Listening</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="listen" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Participation.</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="attitude" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Teamwork.</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="team" class="form-control" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Homeworks</td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="text-center"><span class="fa fa-star"></span></td>
                                    <td class="hidden"><input type="text" id="time" class="form-control" value="0"></td>
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

            <div class="col-sm-6">
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

            <!-- <div class="col-sm-4">
                <div class="well card">
                    <div class="card-title">
                        <h4 class="text-center">Periodo Mayo-Junio</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-center"><strong>Tema:</strong> Verb to Be</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque sit alias iste quisquam porro odio accusantium placeat libero itaque eius, odit molestias corporis pariatur ullam quibusdam harum nulla impedit labore.</p>
                        <h5 class="text-center"><strong>Fecha de Evaluación:</strong> 20 JUN 2017</h5>
                    </div>
                    <div class="card-footer">
                        <label> Vista Detallada</label>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>