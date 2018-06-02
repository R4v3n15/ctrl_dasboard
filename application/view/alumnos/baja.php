<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Alumnos de Baja</h5>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- <button type="button" id="button" class="btn btn-sm btn-info">Send <i class="fa fa-send"></i></button> -->
                <table id="example" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr class="bg-info">
                            <th class="text-center">No.</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Edad</th>
                            <th class="text-center">Sexo</th>
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Tutor</th>
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
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCenterTitle">Dar de Alta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <p class="text-center"> 
                            Dara de alta a:<br> 
                            <strong id="nombre_alumno"></strong>.<br> 
                            ¿Desea continuar con esta acción?
                        </p>
                <input type="hidden" id="num_alumno">
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" id="reactiveStudent" class="btn btn-primary">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</div>