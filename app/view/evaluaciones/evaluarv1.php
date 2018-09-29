<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?= Config::get('URL'); ?>dashboard">Inicio</a></li>
        <li><a href="<?= Config::get('URL'); ?>alumno">Alumnos</a></li>
        <li><a href="<?= Config::get('URL'); ?>evaluaciones/index/<?= $this->alumno;?>" class="active">Calificaciones</a></li>
        <li><a href="javascript:void(0)" class="active">Evaluar</a></li>
    </ol>
    <div class="well"> 
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <h3 class="text-center text-primary">HOJA DE EVALUACIÓN</h3>
            <form action="<?= Config::get('URL'); ?>evaluaciones/guardarEvaluacion" method="POST">
            <div class="row">
                <div class="col-sm-4" style="margin-bottom: 10px;">
                    <div class="form-group">
                        <label class="col-md-3 text-right">Periodo:</label>
                        <div class="col-md-9">
                            <select name="month_from" class="form-control">
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="margin-bottom: 10px;">
                    <div class="form-group">
                        <label class="col-md-3 text-right">A:</label>
                        <div class="col-md-9">
                            <select name="mont_to" class="form-control">
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="margin-bottom: 10px;">
                    <div class="form-group">
                        <label class="col-md-3 text-right">Ciclo:</label>
                        <div class="col-md-9">
                            <select name="ciclo" class="form-control">
                                <option value="<?= date('Y') ?> A"><?= date('Y') ?> A</option>
                                <option value="<?= date('Y') ?> B"><?= date('Y') ?> B</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="margin-bottom: 10px;">
                    <div class="form-group">
                        <label class="col-md-3 text-right">Maestro:</label>
                        <div class="col-md-9">
                            <input type="text" id="id_teacher" name="teacher" class="form-control" required="true">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="margin-bottom: 10px;">
                    <div class="form-group">
                        <label class="col-md-3 text-right">Alumno:</label>
                        <div class="col-md-9">
                            <input type="text" id="id_student" name="student" class="form-control" required="true">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="margin-bottom: 10px;">
                    <div class="form-group">
                        <label class="col-md-3 text-right">Grupo:</label>
                        <div class="col-md-9">
                            <input type="text" id="id_group" name="group" class="form-control" required="true">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="margin-bottom: 15px;">
                    <div class="form-group">
                        <label class="col-md-1 text-right">Tema:</label>
                        <div class="col-md-10">
                            <input type="text" id="id_subject" name="subject" class="form-control" required="true">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr class="tb_purple">
                                <th>Achievement</th>
                                <th class="text-center">Excellent (Excelente)</th>
                                <th class="text-center">Good (Bueno)</th>
                                <th class="text-center">Average (Regular)</th>
                                <th class="text-center">Weak (Bajo)</th>
                                <th class="hidden">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Reading</td>
                                <td class="text-center read_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                <td class="text-center read_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                <td class="text-center read_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                <td class="text-center read_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                <td class="hidden"><input type="text" id="reading" name="reading" class="form-control" value="0"></td>
                            </tr>
                            <tr>
                                <td>Writing</td>
                                <td class="text-center write_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                <td class="text-center write_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                <td class="text-center write_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                <td class="text-center write_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="writing" name="writing" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Speaking</td>
                                <td class="text-center speak_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                <td class="text-center speak_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                <td class="text-center speak_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                <td class="text-center speak_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="speaking" name="speaking" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Listening</td>
                                <td class="text-center listen_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                <td class="text-center listen_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                <td class="text-center listen_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                <td class="text-center listen_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="listening" name="listening" class="form-control" value="0">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr class="tb_purple">
                                <th>Effort</th>
                                <th class="text-center">Excellent (Excelente)</th>
                                <th class="text-center">Good (Bueno)</th>
                                <th class="text-center">Average(Regular)</th>
                                <th class="text-center">Weak (Bajo)</th>
                                <th class="hidden">Puntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Reading</td>
                                <td class="text-center read_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center read_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center read_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center read_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="read" name="read" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Writing</td>
                                <td class="text-center write_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center write_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center write_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center write_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="write" name="write" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Speaking</td>
                                <td class="text-center speak_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center speak_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center speak_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center speak_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="speak" name="speak" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Listening</td>
                                <td class="text-center listen_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center listen_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center listen_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center listen_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="listen" name="listen" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Participation.</td>
                                <td class="text-center active_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center active_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center active_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center active_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="attitude" name="attitude" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Teamwork.</td>
                                <td class="text-center team_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center team_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center team_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center team_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="team" name="tean" class="form-control" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Homeworks</td>
                                <td class="text-center homew_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                <td class="text-center homew_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                <td class="text-center homew_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                <td class="text-center homew_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                <td class="hidden">
                                    <input type="text" id="id_homework" name="homework" class="form-control" value="0">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" style="margin-bottom: 15.5rem;">
                <div class="col-sm-12" style="margin-bottom: 0.8rem;">
                    <div class="form-group">
                        <label>Comentario:</label>
                        <textarea id="id_comment" name="comment" rows="3" class="form-control texto" placeholder="Escriba alguna observación en este espacio..."></textarea>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label class="col-md-2 text-right">Tutor:</label>
                        <div class="col-md-10">
                            <input type="text" id="id_tutor" name="tutor" class="form-control" required="true">
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="form-group">
                        <label class="col-md-4 text-right">Fecha de Evaluación:</label>
                        <div class="col-md-6">
                            <input type="text" id="id_date" name="date_eval" class="form-control" required="true">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center" style="margin: 1.5rem 0;">
                    <button type="submit" class="btn btn-main">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
</div>