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
    <div class="row loading d-none">
      <div class="col-12 text-center">
          <img src="<?php echo Config::get('URL'); ?>assets/load.gif" alt="logo" width="75" height="75">
          <h6 class="text-muted">Wait please..</h6>
      </div>
    </div>
    <div class="row login-form">
      <div class="col-12 text-center py-2 header">
          <img class="d-inline-block" src="<?php echo Config::get('URL'); ?>assets/img/logo.png" alt="logo" width="50" height="50">
          <h2 class="text-white d-inline-block m-0" style="font-weight: 620; vertical-align: middle;">NAATIK S.C.</h2>
      </div>
      <div class="col-12 mb-2">
        <form method="post" class="py-3" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
            <div class="form-group">
                <label for="user_name" class="text-muted m-0">Nombre de Usuario:</label>
                <input type="text" 
                       name="user_name"
                       id="user_name" 
                       placeholder="&#xf007;"
                       class="form-control input-icon"
                       autocomplete="off" 
                       required />
            </div>
            <div class="form-group">
                <label for="user_password" class="text-muted m-0">Contraseña:</label>
                <input type="password" 
                       name="user_password"
                       id="user_password" 
                       placeholder="&#xf023;"
                       class="form-control input-icon" 
                       required />
            </div>
           
            <?php if (!empty($this->redirect)) { ?>
                <input type="hidden" name="redirect" value="<?php echo $this->encodeHTML($this->redirect); ?>" />
            <?php } ?>

            <button type="submit" class="btn box-shadow w-100 mt-2 btn-naatik">INGRESAR</button>
        </form>
      </div>
    </div>

</body>
</html>