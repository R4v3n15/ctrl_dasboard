<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Usuarios Naatik</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-secondary">Share</button>
                    <button class="btn btn-sm btn-outline-secondary">Export</button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php $this->renderFeedbackMessages(); ?>
            </div>
            <div class="col-md-8">
                <h4 class="text-center text-info">Nuevo Usuario</h4>
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
                                <option value="1">Director</option>
                                <option value="2">Recepción</option>
                                <option value="3">Maestro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" 
                                   pattern="[a-zA-Z0-9]{2,64}" 
                                   name="user_name"
                                   class="form-control form-control-sm" 
                                   placeholder="Username" 
                                   required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" 
                                   name="user_email"
                                   class="form-control form-control-sm"
                                   placeholder="email address" 
                                   required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="password" 
                                   name="user_password_new"
                                   class="form-control form-control-sm"
                                   pattern=".{5,}" 
                                   placeholder="Password" 
                                   required autocomplete="off" />
                        </div>
                        <div class="col-md-6">
                            <input type="password" 
                                   name="user_password_repeat"
                                   class="form-control form-control-sm"
                                   pattern=".{5,}" 
                                   required 
                                   placeholder="Repeat your password" 
                                   autocomplete="off" />
                        </div>
                    </div>
                    <div class="row justify-content-center my-3">
                        <div class="col-8 col-md-6 text-center">
                            <input type="submit" 
                                   value="Register" 
                                   class="btn btn-sm btn-primary btn-raised center" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <p style="display: block; font-size: 13px; color: #999;">
                    NOTA: Por favor rellene los campos con datos reales. La cuenta de correo es opcional, el cual le puede servir para el inicio de sesión si olvida su nombre de usuario.
                </p>
            </div>
        </div>
    </main>
</div>