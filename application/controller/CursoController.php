<?php

class CursoController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('select2.min&assets/css', 'jquery.timepicker&assets/libs/css', 'pikaday&assets/libs/css'));
        Registry::set('js', array('select2.min&assets/js', 
                                  'jquery.timepicker.min&assets/libs/js',
                                  'moment.min&assets/libs/js', 
                                  'pikaday.min&assets/libs/js',
                                  'cursos&assets/js'));
    }

    public function index() {
        $this->View->render('cursos/index', array(
            'user_type' => Session::get('user_type'),
            'dias'      => CursoModel::getDays(),
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getGroups(),
            'teachers'  => MaestroModel::getTeachers()
        ));
    }

    public function obtenerClases(){
        CursoModel::getClases(Request::post('page'));
    }

    public function formNewClase() {
        $this->View->renderWithoutHeaderAndFooter('cursos/nuevaclase', array(
            'dias'      => CursoModel::getDays(),
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getGroups(),
            'maestros'  => MaestroModel::getTeachers()
        ));
    }

    public function nuevaClase() {
        if (Request::post('curso') && Request::post('grupo') && Request::post('f_inicio') && 
            Request::post('f_fin') && Request::post('ciclo') && Request::post('h_inicio') && 
            Request::post('h_salida')) {
            $this->View->renderJSON(CursoModel::addNewClass(
                                        Request::post('curso'), 
                                        Request::post('grupo'), 
                                        Request::post('f_inicio'), 
                                        Request::post('f_fin'), 
                                        Request::post('ciclo'),
                                        (array)Request::post('dias'),
                                        Request::post('h_inicio'),
                                        Request::post('h_salida'),
                                        Request::post('c_normal'),
                                        Request::post('c_promocional'),
                                        Request::post('inscripcion'),
                                        Request::post('maestro')
            ));
        } else {
            $this->View->renderJSON(
                                array(
                                    'success' => false, 
                                    'message' => '&#x2718; Error: falta información verifique intente de nuevo.'));
        }
    }

    public function formEditarClase() {
        // H::p(CursoModel::getClass(Request::post('clase')));
        $this->View->renderWithoutHeaderAndFooter('cursos/editarclase', array(
            'clase'     => CursoModel::getClass(Request::post('clase')),
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getGroups(),
            'maestros'  => MaestroModel::getTeachers()
        ));
    }

    public function formReiniciarClase() {
        $this->View->renderWithoutHeaderAndFooter('cursos/reiniciarclase', array(
            'clase'     => CursoModel::getClass(Request::post('clase')),
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getGroups(),
            'maestros'  => MaestroModel::getTeachers()
        ));
    }

    public function actualizarClase() {
        if (Request::post('clase_id') && Request::post('curso') && Request::post('grupo') && Request::post('f_inicio') && Request::post('f_fin') && Request::post('ciclo') && Request::post('h_inicio') && Request::post('h_salida')) {
            CursoModel::updateClass(
                Request::post('clase_id'),
                Request::post('curso'), 
                Request::post('grupo'),
                Request::post('horario'),
                Request::post('f_inicio'), 
                Request::post('f_fin'), 
                Request::post('ciclo'),
                (array)Request::post('dias'),
                Request::post('h_inicio'),
                Request::post('h_salida'),
                Request::post('inscripcion'),
                Request::post('maestro'));
            Redirect::to('curso/index');
        } 
    }

    public function reiniciarClase() {
        if (Request::post('clase_id') && Request::post('f_inicio') && Request::post('f_fin') && Request::post('ciclo') && Request::post('h_inicio') && Request::post('h_salida')) {
            CursoModel::restartClass(
                Request::post('clase_id'),
                Request::post('curso'), 
                Request::post('grupo'),
                Request::post('horario'),
                Request::post('f_inicio'), 
                Request::post('f_fin'), 
                Request::post('ciclo'),
                (array)Request::post('dias'),
                Request::post('h_inicio'),
                Request::post('h_salida'),
                Request::post('c_normal'),
                Request::post('c_promocional'),
                Request::post('inscripcion'),
                Request::post('maestro'));
            Redirect::to('curso');
        } else {
            Session::add('feedback_negative', 'Falta información para completar proceso');
            Redirect::to('curso');
        }
    }

    public function agregarMaestro(){
        echo json_encode(CursoModel::setTeacher(
                                        Request::post('clase'),
                                        Request::post('maestro')
                                    ));
    }

    //Obtener numero de alumnos inscritos en la clase.
    public function obtenerAlumnosClase(){
        $this->View->renderJSON(CursoModel::getNumberStudentsByClass(Request::post('clase')));
    }

    public function moverClase(){
        if (Request::post('clase')) {
            $this->View->renderJSON(CursoModel::moveClass(Request::post('clase')));
        } else {
            $this->View->renderJSON(false);
        }
    }

    public function eliminarClase(){
        $this->View->renderJSON(CursoModel::deleteClass(Request::post('clase')));
    }

    public function nuevoCurso() {
        CursoModel::newCourse(Request::post('curso'));
    }

    public function obtenerCursos() {
        CursoModel::getCursos();
    }

    public function editarCurso(){
        CursoModel::updateCourse(Request::post('id'), Request::post('curso'));
    }

    public function nuevoGrupo() {
        CursoModel::newGroup(Request::post('grupo'));
    }

    public function obtenerGrupos(){
        CursoModel::getGrupos();
    }

    public function editarGrupo(){
        CursoModel::updateGroup(Request::post('id'), Request::post('grupo'));
    }

    public function eliminarGrupo(){
        CursoModel::deleteGroup(Request::post('grupo'));
    }

}
