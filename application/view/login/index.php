<!DOCTYPE html>
<html lang="es">
<head>
    <title>Control</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="icon" href="<?php echo Config::get('URL'); ?>favicon.ico"> -->
    <!-- CSS -->
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/floating-labels.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/main.css" rel="stylesheet">
</head>
<body>
    <form action="<?php echo Config::get('URL'); ?>login/login" method="post" class="form-signin px-5">
        <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <!-- <img class="mb-3" src="<?php echo Config::get('URL'); ?>assets/img/logo.png" alt="logo" width="65" height="65"> -->
            </div>
        </div>
        <div class="form-label-group">
            <input type="text" 
                   name="user_name"
                   id="user_name" 
                   placeholder="&#xf007; Usuario o email"
                   class="form-control form-control-sm input-icon"
                   autocomplete="off" 
                   required />
            <label for="user_name">Usuario</label>
        </div>
        <div class="form-label-group">
            <input type="password" 
                   name="user_password"
                   id="user_password" 
                   placeholder="&#xf023; Contraseña"
                   class="form-control form-control-sm input-icon" 
                   required />
            <label for="user_password">Password</label>
        </div>
       
        <div class="col-12 mb-3">
            <div class="custom-control custom-checkbox">
                <input type="checkbox"
                       name="set_remember_me_cookie" 
                       class="custom-control-input remember-me-checkbox"
                       data-toggle='tooltip'
                       data-placement='bottom'
                       title='Guardar sesión' 
                       id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Recordar sesión</label>
            </div>
        </div>
        <?php if (!empty($this->redirect)) { ?>
            <input type="hidden" name="redirect" value="<?php echo $this->encodeHTML($this->redirect); ?>" />
        <?php } ?>

        <input type="submit" class="btn btn-info btn-raised w-100" value="Ingresar"/>
    </form>

</body>
</html>