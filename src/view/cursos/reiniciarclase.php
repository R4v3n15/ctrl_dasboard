<?php if ($this->clase) { ?>
<h5 class="text-center">
    CLASE: <strong class="text-info"><?= $this->clase->course.' '.$this->clase->group_name; ?></strong>
</h5>
<form action="<?= Config::get('URL'); ?>curso/reiniciarClase" id="frm_update_clase" method="POST" class="form-horizontal">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="f_inicio" class="col-sm-12">Fecha de Inicio: </label>
                <div class="col-sm-12">
                    <input type="text" 
                           id="restartdate_init" 
                           class="form-control"
                           placeholder="Inicia.." 
                           name="f_inicio"
                           required>
                    <input type="hidden" name="clase_id" value="<?= $this->clase->class_id; ?>">
                    <input type="hidden" name="curso" value="<?= $this->clase->course_id; ?>">
                    <input type="hidden" name="grupo" value="<?= $this->clase->group_id; ?>">
                    <input type="hidden" name="horario" value="<?= $this->clase->schedul_id; ?>">
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="f_fin" class="col-sm-12 control-label">Fecha Fin: </label>
                <div class="col-sm-12">
                    <input type="text" 
                           id="restartdate_end" 
                           class="form-control"
                           placeholder="Finaliza.." 
                           name="f_fin" 
                           required>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="periodo" class="col-sm-12">Periodo: </label>
                <div class="col-sm-12">
                    <select class="form-control" name="ciclo" required>
                       <?php $anioAnt=date("Y")-1; $anioNext=date("Y")+1;?>

                       <option value="<?= $anioNext;?> A"><?= $anioNext; ?> A</option>
                       <option value="<?= $anioNext;?> B"><?= $anioNext; ?> B</option>
                       <option value="<?= date('Y');?> A"><?= date("Y"); ?> A</option>
                       <option value="<?= date('Y');?> B"><?= date("Y"); ?> B</option>
                       <option value="<?= $anioAnt;?> A"><?= $anioAnt; ?> A</option>
                       <option value="<?= $anioAnt;?> B"><?= $anioAnt; ?> B</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="dias" class="col-sm-12 control-label">Dias: </label>
                <div class="col-sm-12">
                    <select name="dias[]" id="dias_list" style="width: 100%;" class="form-control" multiple>
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
               <label class="col-12">Costo Normal: </label>
               <div class="col-12">
                  <input type="text" 
                         class="form-control" 
                         name="c_normal" 
                         id="c_normal" 
                         value="<?= $this->clase->costo_normal; ?>">
               </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="form-group">
               <label class="col-12 control-label">Costo Promocional: </label>
               <div class="col-12">
                  <input type="text" 
                         class="form-control" 
                         name="c_promocional" 
                         id="c_promocional"
                         value="<?= $this->clase->costo_promocional; ?>">
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
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col-6 col-md-4 text-center">
            <button class='btn btn-secondary' id="cancel_restart" title='Cancelar'>Cancelar</button>
        </div>
        <div class="col-6 col-md-4 text-center">
            <input type="submit" id="save_changes" class="btn btn-md btn-success" value="Reiniciar">
        </div>
    </div>
</form>

<?php } else { ?>
    <h5 class="text-center text-info">Datos de la clase no encontados..</h5>
<?php } ?>