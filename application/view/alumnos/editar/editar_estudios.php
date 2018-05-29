<h4>Datos Academicos</h4>
<?php if ($this->informacion): ?>
<form action="<?= Config::get('URL'); ?>alumno/actualizarDatosAcademicos" method="POST" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-6">Ocupación:
            <input type="hidden" name="alumno" value="<?= $alumno->id; ?>">
            <select class="form-control" name="ocupacion">
                <option <?= $alumno->ocupation == 'Estudiante' ? 'selected' : ''; ?> value="Estudiante">Estudio</option>
                <option <?= $alumno->ocupation == 'Trabajador' ? 'selected' : ''; ?> value="Trabajador">Trabajo</option>
                <option <?= $alumno->ocupation == 'Ninguno' ? 'selected' : ''; ?> value="Ninguno">Ninguno</option>
            </select>
        </label>
        <label class="col-sm-6">Lugar de Trabajo/Estudio: 
            <input type="tel" 
                   class="form-control"  
                   name="lugar_trabajo" 
                   value="<?= $alumno->workplace; ?>">
        </label>
    </div>
    <div class="form-group">
        <label class="col-sm-6">Nivel de Estudios: 
            <select class="form-control" id="nivel" name="nivel_estudio">
                <option value="">Seleccione...</option>
                <option <?= $alumno->study == 'Preescolar' ? 'selected' : ''; ?> value="Preescolar">Preescolar</option>
                <option <?= $alumno->study == 'Primaria' ? 'selected' : ''; ?> value="Primaria">Primaria</option>
                <option <?= $alumno->study == 'Secundaria' ? 'selected' : ''; ?> value="Secundaria">Secundaria</option>
                <option <?= $alumno->study == 'Bachillerato' ? 'selected' : ''; ?> value="Bachillerato">Bachillerato</option>
                <option <?= $alumno->study == 'Licenciatura' ? 'selected' : ''; ?> value="Licenciatura">Licenciatura</option>
            </select>
        </label>
        <label class="col-sm-6">Último Grado Estudio: 
            <select class="form-control" name="grado_estudio">
                <option value="">Seleccione...</option>
                <option <?= $alumno->grade == 'Primer Año' ? 'selected' : ''; ?> value="Primer Año">Primer Año.</option>
                <option <?= $alumno->grade == 'Segundo Año' ? 'selected' : ''; ?> value="Segundo Año">Segundo Año.</option>
                <option <?= $alumno->grade == 'Tercer Año' ? 'selected' : ''; ?> value="Tercer Año">Tercer Año.</option>
                <option <?= $alumno->grade == 'Cuarto Año' ? 'selected' : ''; ?> value="Cuarto Año">Cuarto Año.</option>
                <option <?= $alumno->grade == 'Quinto Año' ? 'selected' : ''; ?> value="Quinto Año">Quinto Año.</option>
                <option <?= $alumno->grade == 'Sexto Año' ? 'selected' : ''; ?> value="Sexto Año">Sexto Año.</option>
                <option <?= $alumno->grade == 'Concluido' ? 'selected' : ''; ?> value="Concluido">Concluido.</option>
            </select>
        </label>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </div>
</form>
<?php else: ?>
    <h5 class="text-center text-secondary">
        No se encontró información academica del alumno.
    </h5>
<?php endif ?>
