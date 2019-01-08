<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Hoja de Evaluación</h5>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php $this->renderFeedbackMessages(); ?>
            </div>
            <div class="col-md-12">
                <form action="<?= _root(); ?>evaluaciones/guardarEvaluacion" method="POST">
                    <div class="row">
                        <div class="col-sm-9 col-md-8">
                            <div class="form-group">
                                <label for="nivel" class="col-12">Periodo: </label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon1">De: </span>
                                    </div>
                                    <select name="month_from" class="form-control">
                                        <option hidden>Seleccione..</option>
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
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon1">A: </span>
                                    </div>
                                    <select name="mont_to" class="form-control">
                                        <option hidden>Seleccione..</option>
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
                        <div class="col-sm-3 col-md-4">
                            <div class="form-group">
                                <label for="nivel" class="col-12">&nbsp;</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon1">Ciclo: </span>
                                    </div>
                                    <select name="ciclo" class="form-control">
                                        <option value="<?= date('Y') ?> A"><?= date('Y') ?> A</option>
                                        <option value="<?= date('Y') ?> B"><?= date('Y') ?> B</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon1">Alumno: </span>
                                    </div>
                                    <input type="hidden" id="student_id" name="student_id" class="form-control" value="<?= $this->student_id; ?>" required="true">
                                    <input type="text" id="student_name" name="student_name" class="form-control" value="<?= $this->student_name; ?>" required="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon1">Grupo: </span>
                                    </div>
                                    <input type="text" id="id_group" name="group" class="form-control" required="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon1">Maestro: </span>
                                    </div>
                                    <input type="text" id="id_teacher" name="teacher" class="form-control" required="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="reference-addon8">Tema: </span>
                                    </div>
                                    <input type="text" id="id_subject" name="subject" class="form-control" required="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover table-striped table-slim">
                                <thead>
                                    <tr class="bg-purple">
                                        <th>Achievement</th>
                                        <th class="text-center">Excellent (Excelente)</th>
                                        <th class="text-center">Good (Bueno)</th>
                                        <th class="text-center">Average (Regular)</th>
                                        <th class="text-center">Weak (Bajo)</th>
                                        <th hidden>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Reading</td>
                                        <td class="text-center read_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center read_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center read_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center read_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                        <td hidden><input type="text" id="reading" name="reading" class="form-control" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Writing</td>
                                        <td class="text-center write_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center write_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center write_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center write_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="writing" name="writing" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Speaking</td>
                                        <td class="text-center speak_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center speak_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center speak_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center speak_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="speaking" name="speaking" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Listening</td>
                                        <td class="text-center listen_achiev" data-val="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center listen_achiev" data-val="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center listen_achiev" data-val="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center listen_achiev" data-val="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="listening" name="listening" class="form-control" value="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover table-striped table-slim">
                                <thead>
                                    <tr class="bg-purple">
                                        <th>Effort</th>
                                        <th class="text-center">Excellent (Excelente)</th>
                                        <th class="text-center">Good (Bueno)</th>
                                        <th class="text-center">Average(Regular)</th>
                                        <th class="text-center">Weak (Bajo)</th>
                                        <th hidden>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Reading</td>
                                        <td class="text-center read_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center read_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center read_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center read_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="read" name="read" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Writing</td>
                                        <td class="text-center write_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center write_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center write_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center write_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="write" name="write" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Speaking</td>
                                        <td class="text-center speak_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center speak_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center speak_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center speak_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="speak" name="speak" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Listening</td>
                                        <td class="text-center listen_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center listen_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center listen_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center listen_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="listen" name="listen" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Participation.</td>
                                        <td class="text-center active_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center active_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center active_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center active_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="attitude" name="attitude" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Teamwork.</td>
                                        <td class="text-center team_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center team_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center team_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center team_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="team" name="tean" class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Homeworks</td>
                                        <td class="text-center homew_effort" data-opt="1"><span class="fa fa-star"></span></td>
                                        <td class="text-center homew_effort" data-opt="2"><span class="fa fa-star"></span></td>
                                        <td class="text-center homew_effort" data-opt="3"><span class="fa fa-star"></span></td>
                                        <td class="text-center homew_effort" data-opt="4"><span class="fa fa-star"></span></td>
                                        <td hidden>
                                            <input type="text" id="id_homework" name="homework" class="form-control" value="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 col-md-10 col-lg-9">
                            <div class="form-group">
                                <label>Comentario:</label>
                                <textarea id="id_comment" name="comment" rows="2" class="form-control" placeholder="Escriba alguna observación en este espacio..."></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-5 col-lg-5">
                            <div class="form-group">
                                <label>Nombre del tutor:</label>
                                <input type="text" id="id_tutor" name="tutor" class="form-control form-control-sm" required="true">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-5 col-lg-4">
                            <div class="form-group">
                                <label>Fecha de evaluación:</label>
                                <input type="text" 
                                       id="evaluation_date" 
                                       name="evaluation_date" 
                                       class="form-control form-control-sm" 
                                       required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center border-top mt-2 pt-4 mb-5">
                        <button type="submit" class="btn btn-primary btn-shadown btn-flat-md">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>