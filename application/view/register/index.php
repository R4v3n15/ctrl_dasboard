<div class="container">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <!-- login box on left side -->
    <div class="well">
        <div class="row">
            <div class="col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1">
                <h2 class="text-center text-info">Nuevo Usuario</h2>
                <!-- register form -->
                <form method="post" action="<?php echo Config::get('URL'); ?>register/register_action">
                    <div class="col-md-6">
                    <input type="text" 
                           pattern="[a-zA-Z\s]{3,60}" 
                           name="real_name"
                           class="form-control" 
                           placeholder="Nombre(s)" 
                           required /><br>
                    </div>
                    <div class="col-md-6">
                    <input type="text" 
                           pattern="[a-zA-Z\s]{2,64}" 
                           name="last_name"
                           class="form-control" 
                           placeholder="Apellido(s)" /><br>
                    </div>
                    <div class="col-md-6">
                    <select name="user_type" class="form-control">
                        <option value="">Seleccione una categoría</option>
                        <option value="1">Director</option>
                        <option value="2">Recepción</option>
                        <option value="3">Maestro</option>
                    </select><br>
                    </div>
                    <div class="col-md-6">
                    <input type="text" 
                           pattern="[a-zA-Z0-9]{2,64}" 
                           name="user_name"
                           class="form-control" 
                           placeholder="Username" 
                           required /><br>
                    </div>
                    <div class="col-md-12">
                    <input type="text" 
                           name="user_email"
                           class="form-control"
                           placeholder="email address" 
                           required /><br>
                    </div>
                    <div class="col-md-6">
                    <input type="password" 
                           name="user_password_new"
                           class="form-control"
                           pattern=".{5,}" 
                           placeholder="Password" 
                           required autocomplete="off" /><br>
                    </div>
                    <div class="col-md-6">
                    <input type="password" 
                           name="user_password_repeat"
                           class="form-control"
                           pattern=".{5,}" 
                           required 
                           placeholder="Repeat your password" 
                           autocomplete="off" /><br>
                    </div>
                    <div class="col-md-4 col-md-offset-4">
                    <input type="submit" 
                           value="Register" 
                           class="btn btn-sm btn-primary btn-raised center" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <p style="display: block; font-size: 13px; color: #999;">
        NOTA: Por favor rellene los campos con datos reales. La cuenta de correo es opcional, el cual le puede servir para el inicio de sesión si olvida su nombre de usuario.
    </p>
</div>
