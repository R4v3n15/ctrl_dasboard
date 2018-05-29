<?php

class ImportarController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('js', array('importar&assets/js'));
    }

    public function index() {
        $this->View->render('importar/index');
    }

    public function importarAlumnos(){
        ImportarModel::importStudents(Request::post('page'));
    }

    public function importarGrupos() {
        echo json_encode(ImportarModel::importGroups());
    }
    
    public function importarClases() {
        $this->View->render('importar/clases');
    }

    public function getClasesList(){
        ImportarModel::getClasesList();
    }

    // public function importarAlumnos() {
    //     Registry::set('js', array('importar&assets/js'));
    //     $this->View->render('importar/index');
    // }

    public function repetidos() {
        // H::getLibrary('paginadorLib');
        // $page = 0;
        // $paginator = new \Paginador();
        
        // $all_data = ImportarModel::getRepeatedStudents();
        // $data = $paginator->paginar($all_data, $page, 20);

        // $this->View->render('importar/repetidos', array(
        //     'repetidos'  => $paginator->paginar($all_data, 0, 20),
        //     'paginacion' => $paginator->getView('pagination_ajax', 'repeated')
        // ));
        
        $this->View->render('importar/repetidos');
    }

    public function listaRepetidos(){
        ImportarModel::getRepeatedStudents();
    }

    public function corregirAlumnoRepetido(){
        ImportarModel::updateNameStudent(Request::post('student'), 
                                          Request::post('name'), 
                                          Request::post('surname'), 
                                          Request::post('lastname'));
    }

    public function importarMaestros() {
        echo json_encode(ImportarModel::getTeachersList());
    }

    public function importarAlumno() {
        echo json_encode(ImportarModel::importStudent(Request::post('alumno')));
    }

}
