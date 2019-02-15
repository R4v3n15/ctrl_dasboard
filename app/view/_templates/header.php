<!DOCTYPE html>
<html lang="es">
<head>
    <title>Control Escolar</title>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= _root(); ?>favicon.ico">
    <!-- CSS -->
    <link href="<?= _root(); ?>assets/libs/css/bootstrap.min.css"  rel="stylesheet">
    <link href="<?= _root(); ?>assets/libs/css/snackbar.min.css"   rel="stylesheet">
    <link href="<?= _root(); ?>assets/libs/css/dashboard.css"      rel="stylesheet">
    <link href="<?= _root(); ?>assets/libs/css/dataTables.min.css" rel="stylesheet">
    <link href="<?= _root(); ?>assets/libs/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="<?= _root(); ?>assets/libs/css/main.css" rel="stylesheet">
    <link href="<?= _root(); ?>assets/libs/css/cards.css" rel="stylesheet">
    <link href="<?= _root(); ?>assets/css/icons.css" rel="stylesheet">
    <?php  
        if(Registry::has('css')){
            Registry::get('css');
        }
    ?>
</head>
<body>
    <?php  if (Session::userIsLoggedIn()): 
        $user     = Session::get('user_name'); 
        $user_type = (int)Session::get('user_type');?>
    
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a id="menu-trigger" 
           class="navbar-brand col-sm-3 col-md-2 mr-0 menu-toggle text-center" 
           href="#menu-toggle"><i class="fa fa-bars"></i> NAATIK SC</a>
        <h6 class="text-secondary">Bienvenid@: <?= strtoupper($user); ?></h6>
        <ul class="navbar-nav px-5">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?= _root(); ?>login/logout">Salir <span data-feather="log-out"></span></a>
            </li>
        </ul>
    </nav>
    <div class="container-fluid" id="wrapper">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar" id="sidebar-wrapper">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= _active($filename, 'dashboard'); ?>" href="<?= _root(); ?>dashboard">
                                <span data-feather="calendar"></span>
                                Calendario <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item accordion" id="accordionExample">
                            <a class="nav-link <?= _active($filename, 'alumnos');?> collapsed"
                                data-toggle="collapse"
                                data-target="#studentsCollapse"
                                id="students_menu"
                                href="javascript:void(0)">
                                <span data-feather="users"></span>
                                Alumnos <span data-feather="chevron-down"></span>
                            </a>
                            <div id="studentsCollapse" 
                                 class="collapse ml-3" 
                                 aria-labelledby="headingTwo" 
                                 data-parent="#accordionExample">
                                <ul class="list-group">
                                    <li class="dropdown-item student-page" id="studentPage">
                                        <a href="<?= _root(); ?>alumnos">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron"></i> Alumnos</a></li>
                                    <?php if ($user_type !== 3): ?>
                                    <li class="dropdown-item student-page" id="studentPage_inscribir">
                                        <a href="<?= _root(); ?>alumnos/inscribir">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron_inscribir"></i> Nuevo</a>
                                    </li>
                                    <li class="dropdown-item student-page" id="studentPage_becados">
                                        <a href="<?= _root(); ?>alumnos/becados">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron_becados"></i> Becados</a>
                                    </li>
                                    <?php if ($user_type === 777): ?>
                                    <li class="dropdown-item student-page" id="studentPage_sep">
                                        <a href="<?= _root(); ?>alumnos/sep">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron_sep"></i> Sep</a>
                                    </li>
                                    <?php endif ?>
                                    <li class="dropdown-item student-page" id="studentPage_baja">
                                        <a href="<?= _root(); ?>alumnos/baja">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron_baja"></i> Baja</a>
                                    </li>
                                    <?php if ($user_type === 777): ?>
                                    <li class="dropdown-item student-page" id="studentPage_egresados">
                                        <a href="<?= _root(); ?>alumnos/egresados">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron_egresados"></i> Egresados</a>
                                    </li>
                                    <?php endif ?>
                                    <li class="dropdown-item student-page" id="studentPage_eliminados">
                                        <a href="<?= _root(); ?>alumnos/eliminados">
                                            <i class="fa fa-chevron-right text-secondary" id="chevron_eliminados"></i> Eliminados</a>
                                    </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        </li>
                        <?php if ($user_type !== 3): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= _active($filename, 'cursos');?>" href="<?= _root(); ?>curso">
                                <span data-feather="book-open"></span>
                                Clases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= _active($filename, 'maestros');?>" href="<?= _root(); ?>maestro">
                                <span data-feather="users"></span>
                                Maestros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= _active($filename, 'padrinos');?>" href="<?= _root(); ?>padrinos">
                                <span data-feather="users"></span>
                                Padrinos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= _active($filename, 'pagos');?>" href="<?= _root(); ?>pagos">
                                <span data-feather="credit-card"></span>
                                Pagos
                            </a>
                        </li>
                        <?php endif ?>
                    </ul>
                    
                    <?php if ($user_type !== 3): ?>
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>REPORTES</span>
                        <a class="d-flex align-items-center text-muted">
                            <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item accordion" id="accordionReports">
                            <a class="nav-link <?= _active($filename, 'alumnos');?> collapsed"
                                data-toggle="collapse"
                                data-target="#reportsCollapse"
                                id="students_menu"
                                href="javascript:void(0)">
                                <span data-feather="file-text"></span>
                                Reportes <span data-feather="chevron-down"></span>
                            </a>
                            <div id="reportsCollapse" 
                                 class="collapse ml-3" 
                                 aria-labelledby="headingTwo" 
                                 data-parent="#accordionReports">
                                <ul class="list-group">
                                    <li class="dropdown-item student-page">
                                        <a href="<?= _root(); ?>reportes/alumnos">
                                            <i class="fa fa-chevron-right text-secondary"></i> Alumnos
                                        </a>
                                    </li>
                                    <li class="dropdown-item student-page">
                                        <a href="<?= _root(); ?>reportes/registro">
                                            <i class="fa fa-chevron-right text-secondary"></i> Registro</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= _root(); ?>dashboard/admin">
                                <span data-feather="database"></span>
                                Administrar BD
                            </a>
                        </li>
                        <?php if ($user_type === 777): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= _root(); ?>user">
                                <span data-feather="user"></span>
                                Mi Perfil
                            </a>
                        </li>
                        <?php endif ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= _root(); ?>register/index">
                                <span data-feather="user-plus"></span>
                                Nuevo Usuario
                            </a>
                        </li>
                        <?php if (_active($filename, 'alumnos')): ?>
                        <li class="nav-item">
                            <a class="nav-link btnInvoiceList" href="javascript:void(0)">
                                <span data-feather="file-text"></span>
                                Facturación
                            </a>
                        </li>
                        <?php endif ?>
                        <?php if (_active($filename, 'alumnos') && $user_type === 777): ?>
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
                            <a class="nav-link" href="<?= _root(); ?>mapa">
                                <span data-feather="map"></span>
                                Mapa
                            </a>
                        </li>
                        <?php endif ?>
                        <?php if (_active($filename, 'pagos')): ?>
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
                    <?php endif ?>
                </div>
            </nav>
        </div>
    <?php endif ?>