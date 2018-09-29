<div class="container">
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)">Inicio</a></li>
        <li><a href="javascript:void(0)">Alumnos</a></li>
        <li><a href="javascript:void(0)" class="active">Calificaciones</a></li>
    </ol>    
    
    <div class="well card">
        <?php $this->renderFeedbackMessages(); ?>
        <h3 class="text-center text-primary">Calificaciones</h3>
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3 text-center loader">
                <h4 class="text-center">Construyendo..</h4>
                <img src="<?= Config::get('URL');?>public/assets/img/loading.gif">
            </div>
        </div>
    </div>
</div>