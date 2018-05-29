<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Dashboard</h5>
        </div>

        <div class="row justify-content-center">
            <div class="col-3 text-center mb-3">
                <button type="button" id="add_wine" class="btn btn-info btn-sm">Agregar</button>
            </div>
            <div class="col-9 text-center mb-3">
                <select name="vino_tipos form-control form-control-sm" id="vino_tipo">
                    <?php foreach ($this->tipos as $tipo): ?>
                        <option value="<?= $tipo->idTipoVino; ?>"><?= $tipo->tipo; ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-12" id="wines_table">
                
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="ModalWine" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Agergar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="form-group">
                            <label for="" class="col-12">Nombre</label>
                            <div class="col-12">
                                <input type="text" class="form-control form-control-sm" id="nombre">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-12">Precio/Costo</label>
                            <div class=" col-12 input-group">
                                <input type="text" class="form-control form-control-sm" id="precio" placeholder="Precio">
                                <input type="text" class="form-control form-control-sm" id="costo" placeholder="Costo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-12">Tipo/Pais</label>
                            <div class=" col-12 input-group">
                                <input type="text" class="form-control form-control-sm" id="tipo" placeholder="Tipo">
                                <input type="text" class="form-control form-control-sm" id="pais" placeholder="Pais">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-12">Specs</label>
                            <div class=" col-12">
                                <input type="text" class="form-control form-control-sm" id="especs" placeholder="Eparney, Valdobbiadene, etc..">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="save_wine" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>