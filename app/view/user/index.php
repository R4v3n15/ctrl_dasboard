<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Perfil de Usuario</h5>
        </div>


        <div class="row justify-content-center">
            <div class="col-8 col-sm-10 col-md-6 col-lg-4">
                <div class="card card-info box-shadow">
                    <div class="card-header"></div>
                    <div class="card-avatar mx-auto">
                        <img src="<?= $this->user_avatar_file; ?>" alt="avatar">
                    </div>
                    <div class="card-body">
                        <p class="text-center"><strong>Email:</strong> <?= $this->user_email; ?></p>
                        <p class="text-center">
                            <strong>Account type:</strong>
                            <?php 
                                switch ((int)$this->user_account_type) {
                                    case 1:
                                        echo 'Administrador';
                                        break;
                                    case 2:
                                        echo 'Control Escolar';
                                        break;
                                    case 3:
                                        echo 'Maestro';
                                        break;
                                    case 7:
                                        echo 'Super Administrador';
                                        break;
                                    default:
                                        echo 'Visitante';
                                        break;
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 p-2">
                <div class="card p-3">
                    <h5 class="text-center text-info">Cambiar Contraseña</h5>
                    <!-- new password form box -->
                    <form method="post" action="<?= _root(); ?>user/changePassword_action" name="new_password_form">
                        <div class="form-group">
                            <label for="change_input_password_current">Contraseña actual:</label>
                            <input type="password" 
                                   class="form-control reset_input" 
                                   id="change_input_password_current" 
                                   name="user_password_current"
                                   pattern=".{6,}"
                                   placeholder="* * * * * *" 
                                   autocomplete="off"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="change_input_password_new">Nueva contraseña (min. 6 caracteres):</label>
                            <input type="password" 
                                   class="form-control reset_input" 
                                   id="change_input_password_new" 
                                   name="user_password_new"
                                   pattern=".{6,}"
                                   placeholder="* * * * * *" 
                                   autocomplete="off"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="change_input_password_repeat">Repita la nueva contraseña:</label>
                            <input type="password" 
                                   class="form-control reset_input" 
                                   id="change_input_password_repeat" 
                                   name="user_password_repeat"
                                   pattern=".{6,}"
                                   placeholder="* * * * * *" 
                                   autocomplete="off"
                                   required>
                        </div>
                        <input type="submit" 
                               name="submit_new_password" 
                               value="Cambiar" 
                               class="btn btn-secondary box-shadown float-right" />
                    </form>
                </div>
            </div>

            <div class="col-6 p-2">
                <div class="card p-3">
                    <h5 class="text-center text-info">Cambiar Nombre de Usuario</h5>

                    <form action="<?= _root(); ?>user/editUserName_action" method="post">
                        <div class="form-group">
                            <label for="user_name">Nuevo Nombre de Usuario: </label>
                            <input type="text" id="user_name" name="user_name" class="form-control" required />
                            <!-- set CSRF token at the end of the form -->
                            <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                        </div>
                
                        <input type="submit" value="Cambiar" class="btn btn-secondary box-shadown float-right" />
                    </form>
                </div>
            </div>

            <div class="col-6 p-2">
                <div class="card p-3">
                    <h5 class="text-center text-info">Cambiar Correo Electronico</h5>
                    <form action="<?= _root(); ?>user/editUserEmail_action" method="post">
                        <div class="form-group">
                            <label for="user_email">Nuevo Correo Electronico: </label>
                            <input type="text" id="user_email" name="user_email" class="form-control" required />
                        </div>
                        <input type="submit" value="Submit" class="btn btn-secondary box-shadown float-right"/>
                    </form>
                </div>
            </div>

            <div class="col-6 p-2">
                <div class="card p-3">
                    <h5 class="text-center text-info">Cambiar Foto</h5>

                    <div class="text-center text-info">
                        Recargue la página si aun no ve su nueva foto.
                    </div>

                    <form action="<?= _root(); ?>user/uploadAvatar_action" method="post" enctype="multipart/form-data">
                        <label for="avatar_file">Seleccione una imagen:</label><br>
                        <input type="file" id="photo_input" name="avatar_file" required />
                        <input type="hidden" name="MAX_FILE_SIZE" value="8000000" /><br>
                        <input type="submit" value="Guaradar"  class="btn btn-secondary box-shadown float-right"/>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>