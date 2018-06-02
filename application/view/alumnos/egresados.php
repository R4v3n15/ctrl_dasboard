<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-danger">Fuera de Servicio</h5>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <h4 class="text-info my-5">
                    E N &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  M A N T E N I M I E N T O
                </h4>
                <img src="<?= Config::get('URL');?>public/assets/img/loading.gif">
            </div>
        </div>
    </main>
</div>

<?php $user_type = (int)Session::get('user_type'); ?>

<?php if ($user_type === 777): ?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)">Inicio</a></li>
        <li><a href="javascript:void(0)">Alumnos</a></li>
        <li><a href="javascript:void(0)" class="active">Egresados</a></li>
    </ol>    
    
    <div class="well card">
        <?php $this->renderFeedbackMessages(); ?>
        <h3 class="text-center text-primary">Egresados</h3>
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3 text-center loader">
                <h4 class="text-center">Construyendo..</h4>
                <img src="<?= Config::get('URL');?>public/assets/img/loading.gif">
            </div>
        </div>
    </div>
</div>
<?php endif ?>