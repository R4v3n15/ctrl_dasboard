<div class="container">
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)">Inicio</a></li>
        <li><a href="javascript:void(0)" class="active">Usuario</a></li>
    </ol> 
    <div class="well">
        <div class="row">
        <div class="col-lg-4">
            <!-- <h3 class="text-center text-primary">Mi Perfil</h3> -->
            <div class="card-primary">
                <?php $this->renderFeedbackMessages(); ?>
                <div class="card-avatar">
                    <img src="<?= $this->user_avatar_file; ?>" alt="avatar">
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
        <div class="col-lg-4">
            <h3 class="text-center text-primary">Cambiar Contraseña</h3>
            <!-- new password form box -->
            <form method="post" 
                  action="<?= Config::get('URL'); ?>user/changePassword_action" 
                  name="new_password_form">
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
        <div class="col-lg-4">
            <h3 class="text-center text-primary">Cambiar Nombre de Usuario</h3>

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
        <div class="col-lg-4">
            <h3 class="text-center text-primary">Cambiar Correo Electronico</h3>
            <form action="<?php echo Config::get('URL'); ?>user/editUserEmail_action" method="post">
                <div class="form-group">
                    <label for="user_email">Nuevo Correo Electronico: </label>
                    <input type="text" id="user_email" name="user_email" class="form-control" required />
                </div>
                <input type="submit" value="Submit" class="btn btn-second btn-raised btn-sm"/>
            </form>
        </div>
        <div class="col-lg-4">
            <h3 class="text-center text-primary">Cambiar Foto</h3>

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
    </div>
</div>
