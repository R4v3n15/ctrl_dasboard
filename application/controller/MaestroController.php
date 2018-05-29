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

    public function maestro() {
        if(Request::post('maestro')){
            echo json_encode(MaestroModel::getTeacher(Request::post('maestro')));
        }
    }

    public function editarMaestro() {
        if(Request::post('user_id') && Request::post('name') && 
           Request::post('user_name') && Request::post('user_password')){
            MaestroModel::updateTeacher(Request::post('user_id'),
                                        Request::post('name'),
                                        Request::post('lastname'),
                                        Request::post('user_email'),
                                        Request::post('user_name'),
                                        Request::post('user_password'));
            Redirect::to('maestro/index');
        } else {
            var_dump(Request::post('user_id'), Request::post('name'), Request::post('lastname'), Request::post('user_name'));
            exit();
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
