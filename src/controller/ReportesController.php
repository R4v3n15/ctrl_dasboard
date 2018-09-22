<?php

class ReportesController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('css',array('buttons.dataTables.min&assets/libs/css','reportes&assets/css'));
        Registry::set('js', array('dataTables.buttons.min&assets/libs/js',
                                  'buttons.print.min&assets/libs/js',
                                  'reportes&assets/js'));
        // Registry::set('js', array('dataTables.buttons.min&assets/libs/js',
        //                           'buttons.print.min&assets/libs/js',
        //                           'pdfmake.min&assets/libs/js',
        //                           'vfs_fonts&assets/libs/js',
        //                           'buttons.html5.min&assets/libs/js',
        //                           'reportes&assets/js'));
    }

    public function index() {
        $this->View->render('maestros/index', array(
            'maestros'  => MaestroModel::getTeachers()
        ));
    }

    public function alumnos() {
        $this->View->render('reportes/alumnos');
    }

    public function getStudentsTable(){
        $this->View->renderJSON(ReportesModel::StudentsTable(Request::post('curso')));
    }

}
