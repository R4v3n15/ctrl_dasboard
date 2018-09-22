<?php

class EvaluacionesController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('icons&assets/css', 'alumnos&assets/css'));
        Registry::set('js', array('jquery.dataTables.min&assets/js', 'select2.min&assets/js', 'evaluaciones&assets/js'));
    }

    public function index($alumno) {
        $this->View->render('evaluaciones/index', array(
            'alumno' => $alumno));
    }

    public function evaluar($alumno){
        $this->View->render('evaluaciones/evaluarv1', array('alumno' => $alumno));
    }

    public function guardarEvaluacion(){
        if (Request::post('month_from') && Request::post('month_to') && Request::post('student') && Request::post('teacher') && Request::post('date_eval')) {

             // TODO: Pending------->
            
        } else {
            Session::add('feedback_negative','Falta Información para completar evaluación.');
        }
    }

    public function formNewClase() {
        $this->View->renderWithoutHeaderAndFooter('cursos/nuevaclase', array(
            'dias'      => CursoModel::getDays(),
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getLevels(),
            'maestros'  => MaestroModel::getTeachers()
        ));
    }

}
