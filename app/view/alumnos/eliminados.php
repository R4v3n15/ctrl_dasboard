<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Alumnos Eliminados</h5>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="example" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr class="">
                            <th class="text-center">No.</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Edad</th>
                            <th class="text-center">Sexo</th>
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Tutor</th>
                            <th class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Edad</th>
                            <th class="text-center">Sexo</th>
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Tutor</th>
                            <th class="text-center">Opciones</th>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalReactiveStudent" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info">
                <h6 class="modal-title mb-0 text-white" id="ModalCenterTitle">Reactivar Alumno</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <p class="text-center"> 
                            Dara de alta a:<br> 
                            <strong id="nameStudentToActivate"></strong>.<br> 
                            ¿Desea continuar con esta acción?
                        </p>
                        <h6 class="text-center text-info">
                            El alumno sera retornado a <strong id="returnStudentTo"></strong>
                        </h6>
                        <input type="hidden" id="idStudentToActivate">
                        <input type="hidden" id="idClassToReturn">
                    </div>
                </div>
            </div>
            <div class="row mb-2 py-2 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat-md" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="button" id="reactiveStudent" class="btn btn-info btn-sm btn-flat-md">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</div>