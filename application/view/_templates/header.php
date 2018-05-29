<!DOCTYPE html>
<html lang="es">
<head>
    <title>Control</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="icon" href="<?php Config::get('URL'); ?>favicon.ico"> -->
    <!-- CSS -->
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/snackbar.min.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/dashboard.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/dataTables.min.css" rel="stylesheet">
    <!-- <link href="<?php echo Config::get('URL'); ?>assets/libs/css/fontawesome.min.css" rel="stylesheet"> -->
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/libs/css/main.css" rel="stylesheet">
    <link href="<?php echo Config::get('URL'); ?>assets/css/icons.css" rel="stylesheet">
    <?php  
        if(Registry::has('css')){
            Registry::get('css');
        }
    ?>
</head>
<body>
    <?php  if (Session::userIsLoggedIn()): 
        $base_url = Config::get('URL'); 
        $user     = Session::get('user_name'); 
        $usr_type = (int)Session::get('user_type');?>
    
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0 menu-toggle text-center" 
           href="#menu-toggle"><i class="fa fa-bars"></i> NAATIK SC</a>
        <h6 class="text-secondary">Wellcome: <?= strtoupper($user); ?></h6>
        <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
        <ul class="navbar-nav px-5">
            <li class="nav-item text-nowrap">
              <a class="nav-link" href="<?= $base_url; ?>login/logout">Salir <span data-feather="log-out"></span></a>
            </li>
        </ul>
    </nav>
    <div class="container-fluid" id="wrapper">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar" id="sidebar-wrapper">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= View::active($filename, 'dashboard') ? 'active': '';?>" href="<?= $base_url; ?>dashboard">
                              <span data-feather="home"></span>
                              Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item accordion" id="accordionExample">
                            <a class="nav-link <?= View::active($filename, 'alumnos') ? 'active': '';?> collapsed"
                                data-toggle="collapse"
                                data-target="#testCollapse"
                                href="javascript:void(0)">
                              <span data-feather="file"></span>
                              Alumnos
                            </a>
                            <div id="testCollapse" 
                                 class="collapse ml-3" 
                                 aria-labelledby="headingTwo" 
                                 data-parent="#accordionExample">
                                <ul class="list-group">
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos">Alumnos</a></li>
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos/inscribir">Nuevo</a></li>
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos/becados">Becados</a></li>
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos/sep">Sep</a></li>
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos/baja">Baja</a></li>
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos/egresados">Egresados</a></li>
                                    <li class="dropdown-item"><a href="<?= $base_url; ?>alumnos/eliminados">Eliminados</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= View::active($filename, 'cursos') ? 'active': '';?>" href="<?= $base_url; ?>curso">
                              <span data-feather="layers"></span>
                              Clases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= View::active($filename, 'maestros') ? 'active': '';?>" href="<?= $base_url; ?>maestro">
                              <span data-feather="users"></span>
                              Maestros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= View::active($filename, 'padrinos') ? 'active': '';?>" href="<?= $base_url; ?>padrinos">
                              <span data-feather="users"></span>
                              Padrinos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= View::active($filename, 'pagos') ? 'active': '';?>" href="<?= $base_url; ?>pagos">
                              <span data-feather="credit-card"></span>
                              Pagos
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>REPORTES</span>
                        <a class="d-flex align-items-center text-muted">
                            <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url; ?>importar">
                              <span data-feather="file-text"></span>
                              Importar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url; ?>importar/repetidos">
                              <span data-feather="file-text"></span>
                              Repetidos
                            </a>
                        </li>
                        <?php if (View::active($filename, 'alumnos')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url; ?>wines">
                              <span data-feather="file-text"></span>
                              Facturación
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                              <span data-feather="file-text"></span>
                              Lista Sep
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                              <span data-feather="file-text"></span>
                              Lista Becados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url; ?>mapa">
                              <span data-feather="file-text"></span>
                              Mapa
                            </a>
                        </li>
                        <?php endif ?>
                        <?php if (View::active($filename, 'pagos')): ?>
                        <li class="nav-item">
                            <a class="nav-link" id="lista-becarios" href="#">
                              <span data-feather="file-text"></span>
                              Becados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lista-adeudos" href="#">
                              <span data-feather="file-text"></span>
                              Adeudos
                            </a>
                        </li>
                        <?php endif ?>
                    </ul>
                </div>
            </nav>
        </div>
    <?php endif ?>