<h5 class="text-center text-info">CREAR NUEVA CLASE</h5>

<form action="<?php echo Config::get('URL');?>curso/nuevaClase" method="POST" class="form-horizontal">
    <div class="row justify-content-center">
        <div class="col-6 col-md-4">
            <div class="form-group">
                <label for="inputname" class="col-12" >Curso: </label>
                <div class="col-12">
                    <select class="form-control"  name="curso" required="true">
                        <option value="" hidden>Seleccione...</option>
                        <?php  
                        if ($this->cursos) {
                            foreach ($this->cursos as $curso) {
                                echo '<option value="'.$curso->course_id.'">'.$curso->course.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
                <label for="inputname" class="col-12 control-label">Grupo: </label>
                <div class="col-12">
                    <select class="form-control" id="" name="grupo" required="true">
                        <option value="" hidden>Seleccione...</option>
                        <?php  
                        if ($this->niveles) {
                            foreach ($this->niveles as $nivel) {
                                echo '<option value="'.$nivel->group_id.'">'.$nivel->group_name.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4">
            <div class="form-group">
                <label for="f_inicio" class="col-12">Fecha de Inicio: </label>
                <div class="col-12">
                    <input type="text" 
                           id="date_init" 
                           class="form-control"
                           placeholder="Inicia.." 
                           name="f_inicio" required>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
                <label for="f_fin" class="col-12 control-label">Fecha Fin: </label>
                <div class="col-12">
                    <input type="text" 
                           id="date_end" 
                           class="form-control"
                           placeholder="Finaliza.." 
                           name="f_fin" required>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4">
            <div class="form-group">
                <label for="periodo" class="col-12">Periodo: </label>
                <div class="col-12">
                    <select class="form-control" name="ciclo" required="true">
                       <?php date("Y"); $anioAnt=date("Y")-1; $anioNext=date("Y")+1; ?>
                       <option value="" hidden>Seleccione...</option>
                       <option value="<?= $anioNext;?> A"><?php echo $anioNext; ?> A</option>
                       <option value="<?= $anioNext;?> B"><?php echo $anioNext; ?> B</option>
                       <option value="<?= date('Y');?> A"><?php echo date("Y"); ?> A</option>
                       <option value="<?= date('Y');?> B"><?php echo date("Y"); ?> B</option>
                       <option value="<?= $anioAnt;?> A"><?php echo $anioAnt; ?> A</option>
                       <option value="<?= $anioAnt;?> B"><?php echo $anioAnt; ?> B</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
                <label for="dias" class="col-12 control-label">Dias: </label>
                <div class="col-12">
                    <select name="dias[]" id="dias" style="width: 100%;" class="form-control" multiple>
                        <?php  
                        if ($this->dias) {
                            foreach ($this->dias as $dia) {
                                echo '<option value="'.$dia->day_id.'">'.$dia->day.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4">
            <div class="form-group">
               <label for="h_inicio" class="col-12">Hora de Inicio: </label>
               <div class="col-12">
                  <input type="text"
                         id="timepick" 
                         name="h_inicio" 
                         class="form-control" 
                         placeholder="2:00" 
                         required>
               </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
               <label for="h_salida" class="col-12 control-label">Hora de Salida: </label>
               <div class="col-12">
                  <input type="text"
                         id="timepick2"
                         name="h_salida" 
                         class="form-control" 
                         placeholder="2:00" required>
               </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4">
            <div class="form-group">
               <label class="col-12">Costo Normal: </label>
               <div class="col-12">
                  <input type="text" 
                         class="form-control" 
                         name="c_normal" 
                         id="c_normal" 
                         placeholder="200">
               </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
               <label class="col-12 control-label">Costo Promocional: </label>
               <div class="col-12">
                  <input type="text" 
                         class="form-control" 
                         name="c_promocional" 
                         id="c_promocional" 
                         placeholder="200">
               </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4">
            <div class="form-group">
               <label class="col-12">Costo Inscripción: </label>
               <div class="col-12">
                  <input type="text" 
                         class="form-control" 
                         name="inscripcion" 
                         id="inscripcion" 
                         placeholder="200">
               </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="form-group">
               <label for="h_salida" class="col-12 control-label">Maestro: </label>
               <div class="col-12">
                  <select class="form-control" id="" name="maestro">
                        <option value="">Seleccione...</option>
                        <?php if ($this->maestros) {
                            foreach ($this->maestros as $maestro) {
                                echo '<option value="'.$maestro->user_id.'">
                                        '.$maestro->name.' '.$maestro->lastname.'
                                      </option>';
                            }
                        } ?>
                    </select>
               </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center mb-5">
        <div class="col-6 col-md-4 text-center">
            <button class='btn btn-secondary cancel_new right' title='Volver'>
                Cancelar
            </button>
        </div>
        <div class="col-6 col-md-4 text-center">
            <input type="submit"  class="btn btn-primary" value="Guardar">
        </div>
    </div>
</form>