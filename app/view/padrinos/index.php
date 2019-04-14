<?php $base_url = Config::get('URL'); ?>
<div class="row" id="page-content-wrapper">
    <main role="main" class="col-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Padrinos</h5>

            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-sm btn-outline-primary" id="btnAddSponsor">
                    <span data-feather="plus"></span>
                    Nuevo
                </button>
            </div>
        </div>
        <div class="col-md-12 p-0" id="sponsors_list">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="<?= Config::get('URL');?>public/assets/img/loader.gif">
                    <h6 class="text-center" style="margin-top: -2.5rem;">Cargando..</h6>
                </div>
            </div>  
        </div>
    </main>
</div>

<div class="modal fade" id="modalAddNewSponsor" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Registrar Padrino</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form id="frmNewSponsor" method="post" class="row">
                            <div class="col-md-6">
                                <label>Nombre(s):</label>
                                <input type="text" 
                                       pattern="[a-zA-Z\s]{3,60}" 
                                       name="sponsor_name"
                                       class="form-control" 
                                       placeholder="Nombre(s)" 
                                       required /><br>
                            </div>
                            <div class="col-md-6">
                                <label>Apellido(s):</label>
                                <input type="text" 
                                       pattern="[a-zA-Z\s]{2,64}" 
                                       name="sponsor_lastname"
                                       class="form-control" 
                                       placeholder="Apellido(s)" /><br>
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electronico:</label>
                                <input type="email" 
                                       name="sponsor_email"
                                       class="form-control"
                                       placeholder="Correo Electronico" /><br>
                            </div>
                            <div class="col-md-6">
                                <label>Tipo:</label>
                                <input type="text" 
                                       name="sponsor_type"
                                       class="form-control"
                                       placeholder="Tipo de Padrino" 
                                       autocomplete="off" /><br>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="group_name">Descripción:</label>
                                <textarea name="description" 
                                          rows="3" 
                                          class="form-control texto" ></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="becario">Becario</label>
                                <select class="form-control" name="becario">
                                    <option value="" hidden>Seleccione..</option>
                                </select><br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="saveNewSponsor" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="modalAddNewSponsor" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title">Registrar Nuevo Padrino</h4>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <form id="frmNewSponsor" method="post">
                        <div class="col-md-6">
                            <label>Nombre(s):</label>
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{3,60}" 
                                   name="sponsor_name"
                                   class="form-control" 
                                   placeholder="Nombre(s)" 
                                   required /><br>
                        </div>
                        <div class="col-md-6">
                            <label>Apellido(s):</label>
                            <input type="text" 
                                   pattern="[a-zA-Z\s]{2,64}" 
                                   name="sponsor_lastname"
                                   class="form-control" 
                                   placeholder="Apellido(s)" /><br>
                        </div>
                        <div class="col-md-6">
                            <label>Correo Electronico:</label>
                            <input type="email" 
                                   name="sponsor_email"
                                   class="form-control"
                                   placeholder="Correo Electronico" /><br>
                        </div>
                        <div class="col-md-6">
                            <label>Tipo:</label>
                            <input type="text" 
                                   name="sponsor_type"
                                   class="form-control"
                                   placeholder="Tipo de Padrino" 
                                   autocomplete="off" /><br>
                        </div>
                        <div class="col-md-12">
                            <label for="group_name">Descripción:</label>
                            <textarea name="description" 
                                      rows="3" 
                                      class="form-control texto" ></textarea>
                        </div>
                        <div class="col-md-8 col-md-offset-2 text-center">
                            <label for="becario">Becario</label>
                            <select class="form-control" name="becario">
                                <option value=""></option>
                            </select><br>
                        </div>
                        
                        <div class="col-md-4 col-md-offset-4">
                            <input type="button"
                                   id="saveNewSponsor"
                                   value="Registrar" 
                                   class="btn btn-md btn-second btn-raised center" />
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div> -->


<div class="modal fade" id="modalEditSponsor" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Editar Información</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form method="post" id="frmEditSponsor" class="row">
                        <div class="col-md-6">
                            <label>Nombre(s):</label>
                            <input type="hidden" class="form-control" id="sponsor_id" name="sponsor_id">
                            <input type="text" 
                                   id="edit_name"
                                   name="edit_name"
                                   class="form-control" 
                                   placeholder="Nombre(s)" 
                                   required /><br>
                        </div>
                        <div class="col-md-6">
                            <label>Apellido(s):</label>
                            <input type="text" 
                                   id="edit_lastname"
                                   name="edit_lastname"
                                   class="form-control" 
                                   placeholder="Apellido(s)" /><br>
                        </div>
                        <div class="col-md-6">
                            <label>Correo Electronico:</label>
                            <input type="text"
                                   id="edit_email"  
                                   name="edit_email"
                                   class="form-control"
                                   placeholder="Correo Electronico" 
                                   required /><br>
                        </div>
                        <div class="col-md-6">
                            <label>Tipo:</label>
                            <input type="text" 
                                   id="edit_type"
                                   name="edit_type"
                                   class="form-control" 
                                   placeholder="Nombre de Usuario" 
                                   required /><br>
                        </div>
                        <div class="col-md-12">
                            <label for="group_name">Descripción:</label>
                            <textarea name="edit_description"
                                      id="edit_description" 
                                      rows="3" 
                                      class="form-control texto" ></textarea>
                        </div>
                        <div class="col-md-12 text-center">
                            <label for="becario">Becario</label>
                            <select class="form-control" name="edit_becario" id="edit_becario">
                                <option value=""></option>
                            </select><br>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="btn_save_sponsor" class="btn btn-info">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteSponsor" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Eliminar Padrino</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center">¿Eliminar <strong id="name_sponsor"></strong>?</h6>
                        <input type="hidden" id="delete_sponsor_id">
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="delete_sponsor" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
