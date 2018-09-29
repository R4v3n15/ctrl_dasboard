<form class="row justify-content-end form-inline">
	<div class="form-group">
        <div class="col-12 input-group mb-1">
            <div class="input-group-append">
                <span class="input-group-text">Buscar:</span>
            </div>
            <select class="form-control" id="filter" name="filter">
                <option value="alumno">Alumno</option>
                <option value="grupo">Grupo</option>
                <option value="tutor">Tutor</option>
                <option value="edad">Edad Alumno</option>
                <option value="escuela">Escuela Alumno</option>
                <option value="grado">Grado Escolar</option>
            </select>
        </div>
    </div>
    <div class="form-group extra-field">
        <div class="col-12 input-group mb-1">
            <input type="text" 
                   class="form-control form-control" 
                   placeholder="Apellido Paterno" 
                   name="field" id="param1">
        </div>
    </div>
    <div class="form-group extra-field">
        <div class="col-12 input-group mb-1">
            <input type="text" 
                   class="form-control form-control" 
                   placeholder="Apellido Materno" 
                   name="field" id="param2">
        </div>
    </div>
    <div class="form-group">
        <div class="col-12 input-group mb-1">
            <input type="text" 
                   class="form-control form-control" 
                   placeholder="Nombre del alumno" 
                   name="field" id="param">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</form>