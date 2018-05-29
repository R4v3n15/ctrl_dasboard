<?php $base_url = Config::get('URL'); ?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?= $base_url; ?>dashboard">Inicio</a></li>
        <li><a href="javascript:void(0)" class="active">Pagos</a></li>
    </ol>    
    <div class="bs-component well well-content">
        <div class="row" style="padding: 15px 5px 0 5px;">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-3">Grupo: </label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm" id="list">
                            <option value="6">TODOS</option>
                            <option value="1">ENGLISH CLUB</option>
                            <option value="2">PRIMARY</option>
                            <option value="3">ADOLESCENTES</option>
                            <option value="4">AVANZADO</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="col-sm-2">Año: </label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm" id="year">
                            <?php foreach ($this->years as $year): ?>
                                <option value="<?= $year->year; ?>"><?= $year->year; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="" class="col-sm-3">Ciclo: </label>
                    <div class="col-sm-7">
                        <select class="form-control form-control-sm" id="ciclo">
                            <option value="" hidden>- - - Seleccione - - -</option>}
                            <option value="B" <?= date('m') < 8 ? 'selected' : ''; ?>>B</option>
                            <option value="A" <?= date('m') > 7 ? 'selected' : ''; ?>>A</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="" class="col-sm-3">Mes:</label>
                    <div class="col-sm-8">
                        <select class="form-control form-control-sm" id="month">
                            
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button type="button" 
                            class="btn btn-second btn-raised btn-sm"
                            id="search_list" 
                            style="margin: 0; padding: 3px 15px;">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>

        <div id="pay_list">
            <h5 class="text-center text-info">Lista de Adeudos...</h5> 
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalPayMonth" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Pago Mensual</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" id="alumno_id" class="form-control">
                        <div class="form-group">
                            <select class="form-control " id="pay_action">
                                <option value="1">Pagar</option>
                                <option value="2">Becado</option>
                                <option value="3">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 col-sm-offset-1 text-center">
                        <button type="button" id="add_in_group" class="btn btn-sm btn-second btn-raised">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="invoice_list" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Facturación</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="invoice_students_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
