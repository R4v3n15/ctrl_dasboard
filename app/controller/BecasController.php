<?php

class BecasController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('jquery.dataTables.min&assets/css', 'maestros&assets/css'));
        Registry::set('js', array('jquery.dataTables.min&assets/js', 'maestros&assets/js'));
    }

    public function index() {
        $this->View->render('maestros/index', array(
            'maestros'    => MaestroModel::getTeachers()
        ));
    }

    public function agregar_beca(){
        if(!Request::post('idStudent')){
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error: Alumno no valido, reporte problema!'));
            exit();
        }

        if(!Request::post('fecha_registro')){
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error: Falta fecha de registro!'));
            exit();
        }

        if (BecasModel::saveScholar(Request::post('idStudent'), Request::post('fecha_registro'), Request::post('idPadrino'))) {
            $this->View->renderJSON(
                            array('success' => true, 
                                  'message' => 'Becario registrado correctamente!'));
            exit();
        } else {
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error al tratar de registrar al becario, reporte problema!'));
        }
    }

    public function agregar_solicitud(){
        if(!Request::post('idStudent')){
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error: Alumno no valido, reporte problema!'));
            exit();
        }

        if(!Request::post('fecha_solicitud')){
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error: Falta fecha de solicitud!'));
            exit();
        }

        if (BecasModel::saveApplicant(Request::post('idStudent'), Request::post('fecha_solicitud'))) {
            $this->View->renderJSON(
                            array('success' => true, 
                                  'message' => 'Solicitante agregado correctamente!'));
            exit();
        } else {
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error al tratar de registrar al solicitante, reporte problema!'));
        }
    }

    public function quitar_beca(){
        if(!Request::post('idStudent')){
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error: Alumno no valido, reporte problema!'));
            exit();
        }

        if (BecasModel::removeScholar(Request::post('idStudent'))) {
            $this->View->renderJSON(
                            array('success' => true, 
                                  'message' => 'Becario eliminado correctamente!'));
            exit();
        } else {
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error al tratar de eliminar al becario, reporte problema!'));
        }
    }

    public function quitar_solicitud(){
        if(!Request::post('idStudent')){
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error: Alumno no valido, reporte problema!'));
            exit();
        }

        if (BecasModel::removeApplicant(Request::post('idStudent'))) {
            $this->View->renderJSON(
                            array('success' => true, 
                                  'message' => 'Solicitante eliminado correctamente!'));
            exit();
        } else {
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error al tratar de eliminar al solicitante, reporte problema!.'));
        }
    }

}
