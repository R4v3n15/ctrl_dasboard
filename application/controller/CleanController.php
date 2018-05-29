<?php

class CleanController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        // Registry::set('css',array('jquery.dataTables.min&assets/css', 'maestros&assets/css'));
        // Registry::set('js', array('jquery.dataTables.min&assets/js', 'maestros&assets/js'));
    }

    public function student() {
        $this->View->renderJSON(CleanModel::deleteStudent(Request::post('student')));
    }

    public function tutor() {
        $this->View->renderJSON(CleanModel::deleteTutor(Request::post('tutor')));
    }

    public function clase() {
        $this->View->renderJSON(CleanModel::deleteClase(Request::post('class_id')));
    }

    public function sponsor() {
        $this->View->renderJSON(CleanModel::deleteSponsor(Request::post('sponsor')));
    }

    public function teacher() {
        $this->View->renderJSON(CleanModel::deleteTeacher(Request::post('teacher')));
    }

}
