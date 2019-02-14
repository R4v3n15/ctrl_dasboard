<?php if ($this->clase) { ?>
<h5 class="text-center">
    CLASE: <strong class="text-info"><?= $this->clase->course.' '.$this->clase->group_name; ?></strong>
</h5>
<form action="<?= Config::get('URL'); ?>curso/actualizarClase" id="frm_update_clase" method="POST" class="form-horizontal">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="curso" class="col-sm-12" >Curso: </label>
                <div class="col-sm-12">
                    <select class="form-control"  name="curso" required="true">
                        <option value="" hidden>Seleccione...</option>
                        <?php
                        if ($this->cursos) {
                            foreach ($this->cursos as $curso) {
                                if ($this->clase->course_id === $curso->course_id) {
                                    echo '<option selected value="'.$curso->course_id.'">'.
                                            $curso->course.
                                         '</option>';
                                } else {
                                    echo '<option value="'.$curso->course_id.'">'.$curso->course.'</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                    <input type="hidden" name="clase_id" value="<?= $this->clase->class_id; ?>">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="grupo" class="col-sm-12 control-label">Grupo: </label>
                <div class="col-sm-12">
                    <select class="form-control" id="" name="grupo" required="true">
                        <option value="" hidden>Seleccione...</option>
                        <?php  
                        if ($this->niveles) {
                            foreach ($this->niveles as $nivel) {
                                if ($this->clase->group_id == $nivel->group_id ) {
                                    echo '<option selected value="'.$nivel->group_id.'">'
                                            .$nivel->group_name.
                                         '</option>';
                                } else {
                                    echo '<option value="'.$nivel->group_id.'">'.$nivel->group_name.'</option>';
                                }
                                
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="f_inicio" class="col-sm-12">Fecha de Inicio: </label>
                <div class="col-sm-12">
                    <input type="text" 
                           id="editdate_init" 
                           class="form-control"
                           placeholder="Inicia.." 
                           name="f_inicio"
                           value="<?= $this->clase->date_init; ?>" 
                           required>
                    <input type="hidden" name="horario" value="<?= $this->clase->schedul_id; ?>">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="f_fin" class="col-sm-12 control-label">Fecha Fin: </label>
                <div class="col-sm-12">
                    <input type="text" 
                           id="editdate_end" 
                           class="form-control"
                           placeholder="Finaliza.." 
                           name="f_fin" 
                           value="<?= $this->clase->date_end; ?>" 
                           required>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="periodo" class="col-sm-12">Periodo: </label>
                <div class="col-sm-12">
                    <select class="form-control" name="ciclo" required="true">
                       <?php $anioAnt=date("Y")-1; $anioNext=date("Y")+1; ?>

                       <option <?= $anioNext.' A' === $this->clase->year ? 'selected' : '';?> 
                               value="<?= $anioNext;?> A"><?= $anioNext; ?> A</option>
                       <option <?= $anioNext.' B' === $this->clase->year ? 'selected' : '';?> 
                               value="<?= $anioNext;?> B"><?= $anioNext; ?> B</option>
                       <option <?= date("Y").' A' === $this->clase->year ? 'selected' : '';?> 
                               value="<?= date('Y');?> A"><?= date("Y"); ?> A</option>
                       <option <?= date("Y").' B' === $this->clase->year ? 'selected' : '';?> 
                               value="<?= date('Y');?> B"><?= date("Y"); ?> B</option>
                       <option <?= $anioAnt.' A' === $this->clase->year ? 'selected' : '';?> 
                               value="<?= $anioAnt;?> A"><?= $anioAnt; ?> A</option>
                       <option <?= $anioAnt.' B' === $this->clase->year ? 'selected' : '';?> 
                               value="<?= $anioAnt;?> B"><?= $anioAnt; ?> B</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="dias" class="col-sm-12 control-label">Dias: </label>
                <div class="col-sm-12">
                    <select name="dias[]" id="days" style="width: 100%;" class="form-control" multiple>
                        <?php  
                            foreach ($this->clase->dias as $dia) {
                                if ($dia->status == 1) {
                                    echo '<option selected value="'.$dia->day_id.'">'.$dia->day.'</option>';
                                } else {
                                    echo '<option value="'.$dia->day_id.'">'.$dia->day.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
               <label for="h_inicio" class="col-md-12">Hora de Inicio: </label>
               <div class="col-md-12">
                  <input type="text"
                         id="timepick3"
                         name="h_inicio" 
                         class="form-control" 
                         placeholder="2:00"
                         value="<?= date('g:i a', strtotime($this->clase->hour_init)); ?>" 
                         required>
               </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
               <label for="h_salida" class="col-md-12 control-label">Hora Salida: </label>
               <div class="col-md-12">
                  <input type="text"
                         id="timepick4"
                         name="h_salida" 
                         class="form-control" 
                         placeholder="2:00" 
                         value="<?= date('g:i a', strtotime($this->clase->hour_end)); ?>" 
                         required>
               </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
               <label class="col-md-12">Costo Inscripción: </label>
               <div class="col-md-12">
                  <input type="text" 
                         class="form-control" 
                         name="inscripcion" 
                         id="inscripcion"
                         value="<?= $this->clase->costo_inscripcion; ?>"
                         placeholder="200">
               </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
               <label for="h_salida" class="col-md-12 control-label">Maestro: </label>
               <div class="col-md-12">
                  <select class="form-control" id="" name="maestro">
                        <option value="">Seleccione...</option>
                        <?php  
                        if ($this->maestros) {
                            foreach ($this->maestros as $maestro) {
                                if ($this->clase->teacher_id === $maestro->user_id) {
                                    echo '<option selected value="'.$maestro->user_id.'">
                                        '.$maestro->name.' '.$maestro->lastname.'
                                      </option>';
                                } else {
                                    echo '<option value="'.$maestro->user_id.'">
                                            '.$maestro->name.' '.$maestro->lastname.'
                                          </option>';
                                }
                            }
                        }
                        ?>
                    </select>
               </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="h_salida" class="col-md-12 control-label">Libro: </label>
                <div class="col-md-12">
                    <input type="text" 
                         class="form-control" 
                         name="libro" 
                         id="libro"
                         value="<?= $this->clase->book; ?>"
                         placeholder="Nombre del libro">
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col-6 col-md-4 text-center">
            <button class='btn btn-secondary' id="cancel_edit" title='Volver'>
                Cancelar
            </button>
        </div>
        <div class="col-6 col-md-4 text-center">
            <input type="submit" id="save_changes" class="btn btn-md btn-primary" value="Actualizar">
        </div>
    </div>
</form>

<?php } else { ?>
    <h5 class="text-center text-info">Datos de la clase no encontados..</h5>
<?php } ?>