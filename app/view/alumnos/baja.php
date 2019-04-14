<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Alumnos de Baja</h5>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- <button type="button" id="button" class="btn btn-sm btn-info">Send <i class="fa fa-send"></i></button> -->
                <table id="example" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr class="">
                            <th width="60" class="text-center">No.</th>
                            <th width="80" class="text-center">Foto</th>
                            <th width="100" class="text-center">Nombre</th>
                            <th width="100" class="text-center">Edad</th>
                            <th width="100" class="text-center">Grupo</th>
                            <th width="100" class="text-center">Tutor</th>
                            <th width="100" class="text-center">Fecha Baja</th>
                            <th width="130" class="text-center">Motivo</th>
                            <th width="100" class="text-center">Opciones</th>
                        </tr>
                    </thead>

                </table>
                
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalSuscribeStudent" tabindex="-1" role="dialog" aria-labelledby="suscribeTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h6 class="modal-title text-white my-0" id="suscribeTitle">Alta de Alumno</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="hidden" class="form-control text-center" id="suscribe_student" />
                                <h6 class="text-center">
                                    ¿Dar de alta a: <br> 
                                    <strong class="text-info" id="suscribe_name"></strong>?
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2 py-2 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat-md" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="button" id="suscribeStudent" class="btn btn-info btn-sm btn-flat-md">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalDeleteStudent" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger py-2">
                <h6 class="modal-title text-white my-0" id="deleteTitle">Eliminar Alumno</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <input type="hidden" class="form-control text-center" id="delete_student" />
                        <h6 class="text-center text-info">
                            ¿Está seguro de querer eliminar a: <br> <strong id="delete_name"></strong>?
                        </h6>
                    </div>
                </div>
            </div>
            <div class="row mb-2 py-2 px-3">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat-md" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-6 text-right">
                    <button type="button" id="deleteStudent" class="btn btn-danger btn-sm btn-flat-md">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
