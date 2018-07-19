<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Administrar Base de Datos</h5>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10 mb-4 px-md-5">
                <div class="alert alert-dismissible fade" id="alert" role="alert">
                  <h6 id="alertcontent" class="text-white text-center"></h6>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-3 text-center">
                <button type="buttom" class="btn btn-info box-shadown" id="createBackup"><i class="fa fa-archive"></i> Respaldar BD</button>
            </div>
            <?php if ($user_type === 777): ?>
            <div class="col-3 text-center">
                <button type="buttom" class="btn btn-warning box-shadown" id="importDatabase"><i class="fa fa-upload"></i> Importar BD</button>
            </div>
            <div class="col-3 text-center">
                <button type="buttom" class="btn btn-danger box-shadown" id="cleanDatabase"><i class="fa fa-trash"></i> Limpiar BD</button>
            </div>
            <div class="col-3 text-center">
                <button type="buttom" class="btn btn-primary box-shadown" id="feedDatabase"><i class="fa fa-database"></i> Actualizar BD</button>
            </div>
            <?php endif ?>
        </div>
    </main>
</div>
