<div class="container">
    <ol class="breadcrumb">
          <li><a href="javascript:void(0)" class="active">Principal</a></li>
    </ol>    
    <div class="well">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?php $this->renderFeedbackMessages(); ?>
                <!-- <button type="button" id="import_teachers" class="btn btn-sm btn-raised btn-success">Importar Maestros</button> -->
            </div>
            <div class="col-md-10 col-md-offset-1" id="students_list">
                
            </div>
        </div>
    </div>
</div>

<div id="detailTask" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Vista Detallada</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="row">
                <div class="modal-footer col-sm-10 col-sm-offset-1 text-center">
                    <button type="button" class="btn btn-sm btn-naatik btn-raised" data-dismiss="modal">Cerrar</button>
                </div>             
            </div>
        </div>
    </div>
</div>

