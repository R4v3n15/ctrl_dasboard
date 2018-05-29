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
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" id="menuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span data-feather="list"></span>
            Nuevo
          </button>
          <div class="dropdown-menu" aria-labelledby="menuButton">
            <a class="dropdown-item" href="#" id="addClase"><span data-feather="plus"></span> Nueva Clase</a>
            <a class="dropdown-item" href="#"><span data-feather="plus"></span> Nuevo Grupo</a>
            <a class="dropdown-item" href="#"><span data-feather="plus"></span> NUevo Curso</a>
          </div>
        </div>
      </div>
    
        <div id="table_result">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="<?= Config::get('URL');?>public/assets/img/loader.gif">
                    <h6 class="text-center" style="margin-top: -2.5rem;">Cargando..</h6>
                </div>
            </div>
        </div>


    </main>
</div>


<!-- M O D A L S -->

<div class="modal fade" id="deleteClass" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Eliminar Clase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center">¿Eliminar <strong id="clase_name"></strong>?</h6>
                        <p class="text-justify text-warning" id="warn_msg"></p>
                        <input type="hidden" id="delete_clase_id">
                    </div>
                </div>
            </div>
            <div class="row mb-5">
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
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Nuevo Curso</h5>
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
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Editar Curso</h5>
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
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title">Nuevo Grupo</h4>
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
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Actualizar Grupo</h5>
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

<div class="modal fade" id="deleteGroup" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Eliminar Grupo</h5>
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





<div class="modal fade" id="template" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        .....
                    </div>
                </div>
            </div>
            <div class="row mb-5">
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