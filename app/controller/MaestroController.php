<?php

class MaestroController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('fileinput.min&assets/libs/css'));
        Registry::set('js', array('fileinput.min&assets/libs/js','maestros&assets/js'));
    }

    public function index() {
        $this->View->render('maestros/index', array(
            'maestros'    => MaestroModel::getTeachers()
        ));
    }

    public function tablaMaestros(){
        MaestroModel::teachersTable(Request::post('page'));
    }

    public function validarUsuario(){
        $this->View->renderJSON(MaestroModel::validateUsername(Request::post('username')));
    }

    public function validarEmail(){
        $this->View->renderJSON(MaestroModel::validateEmail(Request::post('user_email')));
    }

    

    public function maestro() {
        if(Request::post('maestro')){
            echo json_encode(MaestroModel::getTeacher(Request::post('maestro')));
        }
    }

    public function editarMaestro() {
        if(Request::post('user_id') && Request::post('edit_name') && 
           Request::post('edit_user_name') && Request::post('edit_user_password')){
            MaestroModel::updateTeacher(Request::post('user_id'),
                                        Request::post('edit_name'),
                                        Request::post('edit_lastname'),
                                        Request::post('edit_user_email'),
                                        Request::post('edit_user_name'),
                                        Request::post('edit_user_phone'),
                                        Request::post('edit_user_password'));
            Redirect::to('maestro/index');
        } else {
            Session::add('feedback_negative','No se pudo actualizar los datos del maestro por falta de información.');
            Redirect::to('maestro/index');
        }
    }

    public function eliminarMaestro(){
        if(Request::post('maestro')){
            echo json_encode(MaestroModel::deleteTeacher(Request::post('maestro')));
        } else {
            echo json_encode('false');
        }
    }

    public function nuevoMaestro(){
        RegistrationModel::registerNewUser();

        Redirect::to('maestro/index');
    }

}
