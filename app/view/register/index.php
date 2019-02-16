<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Registro de Usuarios</h5>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php $this->renderFeedbackMessages(); ?>
            </div>
            <div class="col-md-8">
                <h4 class="text-center text-info my-3">Nuevo Usuario</h4>
                <!-- register form -->
                <form method="post" action="<?php echo Config::get('URL'); ?>register/register_action">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{3,60}" 
                                   name="real_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Nombre(s)" 
                                   required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{2,64}" 
                                   name="last_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Apellido(s)" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <select name="user_type" class="form-control form-control-sm">
                                <option value="">Seleccione una categoría</option>
                                <!-- <option value="1">Director</option> -->
                                <option value="2">Recepción</option>
                                <option value="3">Maestro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" 
                                   pattern="[a-zA-Z0-9]{2,64}" 
                                   name="user_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Nombre de usuario" 
                                   required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
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
                        <div class="col-md-6 mb-3">
                            <input type="text" 
                                   name="user_email"
                                   class="form-control form-control-sm"
                                   placeholder="Correo Electronico" 
                                   required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="password" 
                                   name="user_password_new"
                                   class="form-control form-control-sm"
                                   pattern=".{5,}" 
                                   placeholder="Contraseña" 
                                   required autocomplete="off" />
                        </div>
                        <div class="col-md-6">
                            <input type="password" 
                                   name="user_password_repeat"
                                   class="form-control form-control-sm"
                                   pattern=".{5,}" 
                                   required 
                                   placeholder="Repita contraseña" 
                                   autocomplete="off" />
                        </div>
                    </div>
                    <div class="row justify-content-center my-4">
                        <div class="col-8 col-md-6 text-center">
                            <input type="submit" 
                                   value="Register" 
                                   class="btn btn-primary box-shadown center" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <p style="display: block; font-size: 13px; color: #999;">
                    NOTA: Por favor rellene los campos con datos reales. La cuenta de correo es opcional, el cual le puede servir para el inicio de sesión si olvida su nombre de usuario o viceversa.
                </p>
            </div>
        </div>
    </main>
</div>