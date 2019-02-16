<div class="row" id="page-content-wrapper">
    <main role="main" class="col-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Maestros</h5>

            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-sm btn-outline-primary" id="newTeacher">
                    <span data-feather="plus"></span> Nuevo
                </button>
            </div>
        </div>
        <div class="col-12">
            <?php $this->renderFeedbackMessages(); ?>
        </div>
        <div class="col-md-12 text-center" id="tabla_maestros">
            <!-- Tabla de Maestros -->
            <div class="row">
                <div class="col-12 text-center">
                    <img src="<?= _root(); ?>public/assets/img/loader.gif">
                    <h6 class="text-center" style="margin-top: -2.5rem;">Cargando..</h6>
                </div>
            </div>  
        </div>
    </main>
</div>

<div class="modal fade" id="modalAddTeacher" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title my-0" id="ModalCenterTitle">Registrar Nuevo Maestro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3 mb-3">
                <form method="post" 
                      action="<?= _root(); ?>maestro/nuevoMaestro"
                      class="row" 
                      enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="file" id="avatar" name="avatar_file" class="form-control form-control-sm" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm  mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon1">Nombre:</span>
                            </div>
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{3,60}" 
                                   name="real_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Nombre(s)"
                                   aria-label="Nombre"
                                   aria-describedby="addon1"
                                   autocomplete="off" 
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon2">Apellido:</span>
                            </div>
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{3,60}" 
                                   name="last_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Apellido(s)"
                                   aria-label="Apellido"
                                   aria-describedby="addon2"
                                   autocomplete="off"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon3">Puesto:</span>
                            </div>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   aria-describedby="addon3" 
                                   aria-label="Puesto" 
                                   disabled value="Maestro">
                            <input type="hidden" class="form-control" name="user_type" value="3">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon2">Teléfono:</span>
                            </div>
                            <input type="phone" 
                                   name="user_phone"
                                   class="form-control form-control-sm" 
                                   placeholder="Número de teléfono"
                                   autocomplete="off"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span id="invalid-username" class="text-danger"></span>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon4">Usuario:</span>
                            </div>
                            <input type="text" 
                                   pattern="[a-zA-Z0-9]{4,64}" 
                                   name="user_name"
                                   id="user_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Nombre de Usuario"
                                   aria-label="Username"
                                   aria-describedby="addon4"
                                   autocomplete="off"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Mínimo 4 caracteres"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span id="invalid-email" class="text-danger"></span>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon5">Correo:</span>
                            </div>
                            <input type="email"
                                   id="user_email" 
                                   name="user_email"
                                   class="form-control form-control-sm" 
                                   placeholder="Correo Electronico"
                                   aria-label="Username"
                                   aria-describedby="addon5"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Ej. maestro@gmail.com"
                                   autocomplete="off" 
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon6">Contraseña:</span>
                            </div>
                            <input type="password" 
                                   name="user_password_new"
                                   class="form-control form-control-sm" 
                                   placeholder="Contraseña"
                                   aria-label="Username"
                                   aria-describedby="addon6"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Mínimo 5 caracteres"
                                   pattern=".{5,}"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon6">Confirmar:</span>
                            </div>
                            <input type="password" 
                                   name="user_password_repeat"
                                   class="form-control form-control-sm" 
                                   placeholder="Repetir Contraseña" 
                                   aria-label="Username"
                                   aria-describedby="addon7"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Mínimo 5 caracteres"
                                   pattern=".{5,}"
                                   required />
                        </div> 
                    </div>
                    <div class="col-12 text-center">
                    <input type="submit" 
                           value="Registrar"
                           id="add_teacher" 
                           class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditTeacher" tabindex="-1" role="dialog" aria-labelledby="ModalEditTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title my-0" id="ModalEditTitle">Actualizar Datos Del Maestro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3 mb-3">
                <form method="post"
                      action="<?= _root(); ?>maestro/editarMaestro"
                      class="row" 
                      enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="avatar">Cambiar foto:</label>
                            <div class="col-sm-12">
                                <input type="file" id="avatar_file" name="avatar_file" class="form-control form-control-sm" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon1">Nombre:</span>
                            </div>
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{3,60}" 
                                   id="edit_name"
                                   name="edit_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Nombre(s)"
                                   aria-label="Nombre"
                                   aria-describedby="addon1" 
                                   required />
                            <input type="hidden" class="form-control" id="user_id" name="user_id">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon2">Apellido:</span>
                            </div>
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{3,60}" 
                                   id="edit_lastname"
                                   name="edit_lastname"
                                   class="form-control form-control-sm" 
                                   placeholder="Apellido(s)"
                                   aria-label="Apellido"
                                   aria-describedby="addon2" 
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon3">Puesto:</span>
                            </div>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   aria-describedby="addon3" 
                                   aria-label="Puesto" 
                                   disabled value="Maestro">
                            <input type="hidden" class="form-control" name="user_type" value="3">
                        </div>
                        
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon2">Teléfono:</span>
                            </div>
                            <input type="phone"
                                   id="edit_user_phone"
                                   name="edit_user_phone"
                                   class="form-control form-control-sm" 
                                   placeholder="Número de teléfono"
                                   autocomplete="off"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon4">Usuario:</span>
                            </div>
                            <input type="text" 
                                   pattern="[a-zA-Z0-9]{2,64}" 
                                   id="edit_user_name"
                                   name="edit_user_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Nombre de Usuario"
                                   aria-label="Username"
                                   aria-describedby="addon4"
                                   autocomplete="off" 
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon5">Correo:</span>
                            </div>
                            <input type="email" 
                                   id="edit_user_email"  
                                   name="edit_user_email"
                                   class="form-control form-control-sm" 
                                   placeholder="Correo Electronico"
                                   aria-label="Username"
                                   aria-describedby="addon5"
                                   autocomplete="off" 
                                   required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon6">Contraseña:</span>
                            </div>
                            <input type="password" 
                                   name="edit_user_password"
                                   id="edit_user_password"
                                   class="form-control form-control-sm" 
                                   placeholder="Contraseña"
                                   aria-label="Username"
                                   aria-describedby="addon6"
                                   pattern=".{5,}"
                                   required />
                        </div>
                    </div>
                    <div class="col-12 text-center">
                    <input type="submit" 
                           value="Actualizar"
                           id="update_teacher" 
                           class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteTeacher" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Eliminar Maestro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center">¿Eliminar a <strong id="teacher_name"></strong>?</h6>
                        <input type="hidden" id="delete_teacher_id">
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="confirm_delete_teacher" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
