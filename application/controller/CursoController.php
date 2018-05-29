<?php

class CursoController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('select2.min&assets/css'));
        Registry::set('js', array('select2.min&assets/js', 'datepicker&assets/libs/js','timepicker.min&assets/libs/js','cursos&assets/js'));
    }

    public function index() {
        $this->View->render('cursos/index', array(
            'user_type' => Session::get('user_type'),
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
        if (Request::post('curso') && Request::post('grupo') && Request::post('f_inicio') && Request::post('f_fin') && Request::post('ciclo') && Request::post('h_inicio') && Request::post('h_salida')) {
            CursoModel::addNewClass(
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
                Request::post('maestro'));
            Redirect::to('curso/index');
        } else {
            Session::add('feedback_negative','Falta Información para completar el registro.');
            Redirect::to('curso/index');
        }
    }

    public function formEditarClase() {
        $this->View->renderWithoutHeaderAndFooter('cursos/editarclase', array(
            'clase'     => CursoModel::getClass(Request::post('clase')),
            'diasclase' => CursoModel::getDaysByClass(Request::post('horario')),
            'dias'      => CursoModel::getDays(),
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

    public function agregarMaestro(){
        echo json_encode(CursoModel::setTeacher(
                                        Request::post('clase'),
                                        Request::post('maestro')
                                    ));
    }

    //Obtener numero de alumnos inscritos en la clase.
    public function obtenerAlumnosClase(){
        echo json_encode(CursoModel::getNumberStudentsByClass(Request::post('clase')));
    }

    public function eliminarClase(){
        echo json_encode(CursoModel::deleteClass(Request::post('clase')));
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
