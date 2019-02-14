<?php 
    $alumno = $this->estudios;
    $clase  = $alumno->clase;
?>
<h5 class="text-center text-secondary">Datos Academicos</h5>
<hr class="mt-1">
<form id="updateStudiesForm" method="POST" class="form-horizontal">
    <div class="form-group row">
        <label class="col-sm-6">Ocupación:
            <input type="hidden" id="student" name="student" value="<?= $alumno->student_id; ?>">
            <select class="form-control form-control-sm" name="ocupation">
                <option <?= $alumno->ocupation == 'Estudiante' ? 'selected' : ''; ?> value="Estudiante">Estudio</option>
                <option <?= $alumno->ocupation == 'Trabajador' ? 'selected' : ''; ?> value="Trabajador">Trabajo</option>
                <option <?= $alumno->ocupation == 'Ninguno' ? 'selected' : ''; ?> value="Ninguno">Ninguno</option>
            </select>
        </label>
        <label class="col-sm-6">Lugar de Trabajo/Estudio: 
            <input type="tel" 
                   class="form-control form-control-sm"  
                   name="workplace" 
                   value="<?= $alumno->workplace; ?>"
                   autocomplete="off">
        </label>
    </div>
    <div class="form-group row">
        <label class="col-sm-6">Nivel de Estudios: 
            <select class="form-control form-control-sm" id="studies" name="studies">
                <option value="">Seleccione...</option>
                <option <?= $alumno->studies == 'Preescolar' ? 'selected' : ''; ?> value="Preescolar">Preescolar</option>
                <option <?= $alumno->studies == 'Primaria' ? 'selected' : ''; ?> value="Primaria">Primaria</option>
                <option <?= $alumno->studies == 'Secundaria' ? 'selected' : ''; ?> value="Secundaria">Secundaria</option>
                <option <?= $alumno->studies == 'Bachillerato' ? 'selected' : ''; ?> value="Bachillerato">Bachillerato</option>
                <option <?= $alumno->studies == 'Licenciatura' ? 'selected' : ''; ?> value="Licenciatura">Licenciatura</option>
            </select>
        </label>
        <label class="col-sm-6">Último Grado Estudio: 
            <select class="form-control form-control-sm" name="lastgrade">
                <option value="">Seleccione...</option>
                <option <?= $alumno->lastgrade == 'Primer Año' ? 'selected' : ''; ?> value="Primer Año">Primer Año.</option>
                <option <?= $alumno->lastgrade == 'Segundo Año' ? 'selected' : ''; ?> value="Segundo Año">Segundo Año.</option>
                <option <?= $alumno->lastgrade == 'Tercer Año' ? 'selected' : ''; ?> value="Tercer Año">Tercer Año.</option>
                <option <?= $alumno->lastgrade == 'Cuarto Año' ? 'selected' : ''; ?> value="Cuarto Año">Cuarto Año.</option>
                <option <?= $alumno->lastgrade == 'Quinto Año' ? 'selected' : ''; ?> value="Quinto Año">Quinto Año.</option>
                <option <?= $alumno->lastgrade == 'Sexto Año' ? 'selected' : ''; ?> value="Sexto Año">Sexto Año.</option>
                <option <?= $alumno->lastgrade == 'Concluido' ? 'selected' : ''; ?> value="Concluido">Concluido.</option>
            </select>
        </label>
    </div>
    <hr class="mt-3"></hr>
    <div class="form-group row">
        <label class="col-12 text-center">Esta inscrito actualmente en:</label>
        <label class="col-sm-6">Curso: 
            <select class="form-control form-control-sm" id="course" name="course">
                <option value="" hidden="">Seleccione..</option>
                <?php if ($this->cursos): ?>
                    <?php foreach ($this->cursos as $curso): ?>
                        <?php if ($clase->course_id == $curso->course_id): ?>
                            <option value="<?= $curso->course_id; ?>" selected><?= $curso->course; ?></option>
                        <?php else: ?>
                            <option value="<?= $curso->course_id; ?>"><?= $curso->course; ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php endif ?>
                <option value="0">- - EN ESPERA - -</option>
            </select>
        </label>
        <label class="col-sm-6">Grupo: 
            <select class="form-control form-control-sm" id="groups" name="class">
                <option value="" hidden="">Seleccione..</option>
                <?php if ($this->grupos): ?>
                    <?php foreach ($this->grupos as $grupo): ?>
                        <?php if ($clase->class_id == $grupo->class_id): ?>
                            <option value="<?= $grupo->class_id; ?>" selected><?= $grupo->group_name; ?></option>
                        <?php else: ?>
                            <option value="<?= $grupo->class_id; ?>"><?= $grupo->group_name; ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php endif ?>
            </select>
        </label>
        <label class="col-sm-6">Fecha de Inscripción:
            <input type="text" 
                   id="fecha_inscripcion" 
                   class="form-control form-control-sm"
                   placeholder="Cuándo inicia el alumno"
                   value="<?= $alumno->created_at; ?>" 
                   name="fecha_inscripcion">
        </label>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <button type="button" id="update_studies" class="btn btn-primary">Actualizar</button>
        </div>
    </div>
</form>

