<?php $show =  $this->user_type === '1' || $this->user_type === '2';?>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Clases</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-success" id="show_clases" data-index="1">Clases</button>
                    <button class="btn btn-sm btn-outline-success" id="show_courses" data-index="2">Cursos</button>
                    <button class="btn btn-sm btn-outline-success" id="show_groups" data-index="4">Grupos</button>
                    <button class="btn btn-sm btn-outline-success" id="t3" data-index="3">Horarios</button>
                </div>
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                        id="menuButton" 
                        data-toggle="dropdown" 
                        aria-haspopup="true" 
                        aria-expanded="false">
                <span data-feather="list"></span>
                Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="menuButton">
                    <a class="dropdown-item" href="#" id="addClase">
                        <span class="sp-blue" data-feather="plus"></span> Nueva Clase</a>
                    <a class="dropdown-item" href="#">
                        <span class="sp-blue" data-feather="plus"></span> Nuevo Grupo</a>
                    <a class="dropdown-item" href="#">
                        <span class="sp-blue" data-feather="plus"></span> Nuevo Curso</a>
                </div>
            </div>
        </div>
        
        <diw class="row">
            <div class="col-12 text-center">
                <?php $this->renderFeedbackMessages(); ?>
                <div class="alert alert-dismissible text-white d-none" id="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p id="alert-content" class=" mt-0 mb-1 mx-2">helper text</p>
                </div>
            </div>
            <div class="col-12">
                <span class="badge badge-success rounded-0">En Curso</span> | 
                <span class="badge badge-danger rounded-0">Terminado</span>
            </div>
            <div class="col-12 text-center" id="table_result">
                <img src="<?= Config::get('URL');?>public/assets/img/loader.gif">
                <h6 class="text-center" style="margin-top: -2.5rem;">Cargando..</h6>
            </div>
        </diw>
    </main>
</div>


<!-- M O D A L S -->
<div class="modal fade" id="modalAddClasse" tabindex="-1" role="dialog" aria-labelledby="ModalClassTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success py-2">
                <h6 class="modal-title text-white m-0" id="ModalClassTitle">NUEVA CLASE</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmCreateClase" method="POST" class="form-horizontal">
                    <div class="row justify-content-center">
                        <div class="col-sm-6 col-md-6 col-lg-4">
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
                        <div class="col-sm-6 col-md-6 col-lg-4">
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

                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="f_inicio" class="col-12">Fecha de Inicio: </label>
                                <div class="col-12">
                                    <input type="text" 
                                           id="date_init" 
                                           class="form-control"
                                           placeholder="Inicia.." 
                                           name="f_inicio"
                                           autocomplete="off" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="f_fin" class="col-12 control-label">Fecha Fin: </label>
                                <div class="col-12">
                                    <input type="text" 
                                           id="date_end" 
                                           class="form-control"
                                           placeholder="Finaliza.." 
                                           name="f_fin"
                                           autocomplete="off" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4">
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
                        <div class="col-sm-6 col-md-6 col-lg-4">
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

                        <div class="col-sm-6 col-md-6 col-lg-4">
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
                        <div class="col-sm-6 col-md-6 col-lg-4">
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

                        <div class="col-sm-6 col-md-6 col-lg-4">
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
                        <div class="col-sm-6 col-md-6 col-lg-4">
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

                        <div class="col-sm-6 col-md-6 col-lg-4">
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
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                               <label for="h_salida" class="col-12 control-label">Maestro: </label>
                               <div class="col-12">
                                  <select class="form-control" id="" name="maestro">
                                        <option value="">Seleccione...</option>
                                        <?php if ($this->teachers) {
                                            foreach ($this->teachers as $maestro) {
                                                echo '<option value="'.$maestro->user_id.'">
                                                        '.ucwords(strtolower($maestro->name)).' '.
                                                        ucwords(strtolower($maestro->lastname)).'
                                                      </option>';
                                            }
                                        } ?>
                                    </select>
                               </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                               <label class="col-12">Libro: </label>
                               <div class="col-12">
                                  <input type="text" 
                                         class="form-control" 
                                         name="libro" 
                                         id="libro" 
                                         placeholder="Nombre del libro">
                               </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center mb-3">
                        <div class="col-6 col-md-4 text-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-6 col-md-4 text-center">
                            <input type="submit"  class="btn btn-primary" value="Guardar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditClasse" tabindex="-1" role="dialog" aria-labelledby="ModalEditTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h6 class="modal-title text-white m-0" id="ModalEditTitle">EDITAR CLASE</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="frmEditClase">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRestartClasse" tabindex="-1" role="dialog" aria-labelledby="ModalRest" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success py-2">
                <h6 class="modal-title text-white m-0" id="ModalRest">REINICIAR CLASE</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="frmRestartClase">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRemoveClass" tabindex="-1" role="dialog" aria-labelledby="ModalRemoveTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning py-2">
                <h6 class="modal-title text-white my-0" id="ModalRemoveTitle">QUITAR CLASE DE LA LISTA</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center">¿Mover <strong id="remove_clase_name"></strong> a lista de terminados?</h6>
                        <input type="hidden" id="remove_clase_id">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="btn_remove_class" class="btn btn-info">Mover</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteClass" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger py-2">
                <h5 class="modal-title text-white my-0" id="ModalCenterTitle">Eliminar Clase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center">¿Eliminar <strong id="clase_name"></strong>?</h6>
                        <p class="text-center text-danger" id="warn_msg"></p>
                        <input type="hidden" id="delete_clase_id">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="btn_delete_class" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCourse" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success py-2">
                <h6 class="modal-title text-white m-0" id="ModalCenterTitle">NUEVO CURSO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <label for="new_course_name">Nombre del Curso:</label>
                        <input type="text" 
                               id="new_course_name" 
                               name="new_course_name" 
                               class="form-control text-center" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="col-6 text-center">
                        <button type="button" id="new_course"  class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCourse" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h6 class="modal-title text-white m-0" id="ModalCenterTitle">EDITAR CURSO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <label for="course_name">Nombre del Curso:</label>
                        <input type="text" 
                               id="course_name" 
                               name="course_name"
                               class="form-control text-center" 
                               required>
                        <input type="hidden" 
                               id="course_id"  
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-2 mb-4">
                <div class="col-6 text-center">
                    <button type="button" id="btn_update_course" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addGroup" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success py-2">
                <h6 class="modal-title text-white m-0">NUEVO GRUPO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 text-center">
                        <label for="new_group_name">Nombre del Grupo:</label>
                        <input type="text" 
                               id="new_group_name" 
                               name="new_group_name" 
                               class="form-control text-center" required>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="modal-footer col-md-10 col-md-offset-1 text-center">
                    <button type="button" class="btn btn-sm btn-naatik btn-raised left" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="new_group" class="btn btn-sm btn-second btn-raised right">Crear</button>
                </div>             
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h6 class="modal-title text-white m-0" id="ModalCenterTitle">ACTUALIZAR GRUPO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10 text-center">
                        <label for="group_name">Nombre del Grupo:</label>
                        <input type="text" 
                               id="group_name"  
                               class="form-control text-center" required>
                        <input type="hidden" 
                               id="group_id"  
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-4 mt-2">
                <div class="col-10 text-center">
                    <button type="button" id="update_group" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteGroup" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger py-2">
                <h6 class="modal-title text-white m-0" id="ModalDeleteTitle">ELIMINAR GRUPO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center">¿Eliminar <strong id="g_name"></strong>?</h6>
                        <input type="hidden" id="delete_group_id">
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="delete_group" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addTeacher" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Agregar Maestro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="form-group">
                            <label for="maestro" class="col-12 text-center">Maestro:</label>
                            <div class="col-sm-12">
                                <select name="maestro" id="maestro" class="form-control">
                                    <option value="" hidden>- - - Seleccione - - -</option>
                                    <?php if ($this->teachers): ?>
                                        <?php foreach ($this->teachers as $teacher): ?>
                                            <option value="<?= $teacher->user_id ?>" >
                                                <?= 
                                                    ucwords(strtolower($teacher->name)).' '.
                                                    ucwords(strtolower($teacher->lastname)); 
                                                ?>
                                            </option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                    <option value="0">- - Pendiente - -</option>
                                </select>
                                <input type="hidden" id="clase_id">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="add_teacher" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>
