<?php

class EvaluacionesController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('pikaday&assets/libs/pikaday/css', 'icons&assets/css'));
        Registry::set('js', array('moment.min&assets/libs/js', 
                                  'pikaday&assets/libs/pikaday', 
                                  'evaluaciones&assets/js'));
    }

    public function st($student){
        $alumno = EvaluacionesModel::getStudentName($student);

        if ($alumno) {
            $this->View->render('evaluaciones/index', array(
                'student_name' => $alumno,
                'student_id'   => $student
            ));
        } else {
            $this->View->render('error/404');
        }
    }

    public function index($alumno) {
        $this->View->render('evaluaciones/index', array(
            'alumno' => $alumno));
    }

    public function ev($student){
        $alumno = EvaluacionesModel::getStudentName($student);
        if ($alumno) {
            $this->View->render('evaluaciones/evaluarv1', array(
                'student_name'   => $alumno,
                'student_id'    => $student
            ));
        } else {
            $this->View->render('error/404');
        }
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
