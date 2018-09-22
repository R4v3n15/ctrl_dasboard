<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Dashboard</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-secondary">Share</button>
                    <button class="btn btn-sm btn-outline-secondary">Export</button>
                </div>
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span> This week
                </button>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="text-center">
                        <img src="<?= $this->user_avatar_file; ?>" class="rounded rounded-circle" alt="avatar" heigth="150" width="150">
                    </div>
                    <div class="card-title">
                        <h3 class="text-center"><?= $this->user_name; ?></h3>
                    </div>
                    <div class="card-body">
                        <p class="text-center"><strong>Email:</strong> <?= $this->user_email; ?></p>
                        <p class="text-center"><strong>Account type:</strong> <?= $this->user_account_type; ?></p>
                    </div> 
                </div>
            </div>

            <div class="col-6 card p-3">
                <h4 class="text-center text-primary">Cambiar Contraseña</h4>
                <!-- new password form box -->
                <form method="post" action="<?= Config::get('URL'); ?>user/changePassword_action" name="new_password_form">
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
                           class="btn btn-second btn-raised btn-sm" />
                </form>
            </div>

            <div class="col-6 card p-3">
                <h4 class="text-center text-primary">Cambiar Nombre de Usuario</h4>

                <form action="<?php echo Config::get('URL'); ?>user/editUserName_action" method="post">
                    <div class="form-group">
                        <label for="user_name">Nuevo Nombre de Usuario: </label>
                        <input type="text" id="user_name" name="user_name" class="form-control" required />
                        <!-- set CSRF token at the end of the form -->
                        <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    </div>
            
                    <input type="submit" value="Cambiar" class="btn btn-second btn-raised btn-sm" />
                </form>
            </div>

            <div class="col-6 card p-3">
                <h4 class="text-center text-primary">Cambiar Correo Electronico</h4>
                <form action="<?php echo Config::get('URL'); ?>user/editUserEmail_action" method="post">
                    <div class="form-group">
                        <label for="user_email">Nuevo Correo Electronico: </label>
                        <input type="text" id="user_email" name="user_email" class="form-control" required />
                    </div>
                    <input type="submit" value="Submit" class="btn btn-second btn-raised btn-sm"/>
                </form>
            </div>

            <div class="col-6 card p-3">
                <h4 class="text-center text-primary">Cambiar Foto</h4>

                <div class="text-center text-info">
                    Recargue la página si aun no ve su nueva foto.
                </div>

                <form action="<?php echo Config::get('URL'); ?>user/uploadAvatar_action" method="post" enctype="multipart/form-data">
                    <label for="avatar_file">Seleccione una imagen:</label>
                    <input type="file" id="photo_input" name="avatar_file" required />
                    <input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
                    <input type="submit" value="Guaradar"  class="btn btn-second btn-raised btn-sm"/>
                </form>
            </div>
        </div>
    </main>
</div>