<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?= Config::get('URL'); ?>dashboard">Principal</a></li>
        <li><a href="<?= Config::get('URL'); ?>alumno">Alumnos</a></li>
        <li><a href="javascript:void(0)" class="active">Bajas</a></li>
    </ol>    
    
    <div class="bs-component">
        <ul class="nav nav-tabs nav-justified nav-submenu menu_student_list" style="margin-bottom: 15px;">
            <li class="active">
                <a href="#checkout" data-toggle="tab">ALUMNOS DE BAJA</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content well well-content">
            <div class="active tab-pane fade in list_checkout" id="checkout_list">
                
            </div>
        </div>
    </div>
</div>

<div id="checkin_st" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&times;&nbsp;</button>
                <h4 class="modal-title text-center">Dar de Alta</h4>
            </div>
            <div class="modal-body">
                <p class="text-center"> Dara de alta a:<br> <strong id="nombre_alumno"></strong>. <br> 
                                        ¿Desea continuar con esta acción?
                </p>
                <input type="hidden" id="num_alumno">
            </div>
            <div class="row">
                <div class="modal-footer col-sm-10 col-sm-offset-1 text-center">
                    <button type="button" id="no_checkin" class="btn btn-sm btn-gray btn-raised left" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="checked_student" class="btn btn-sm btn-second btn-raised right">Dar de Alta</button>
                </div>
            </div>
        </div>
    </div>
</div>