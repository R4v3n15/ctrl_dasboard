<?php

class PagosController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        // Registry::set('css',array('pagos&assets/css'));
        Registry::set('js', array('pagos&assets/js'));
    }

    public function index() {
        $this->View->render('pagos/index', array(
            'cursos' => CursoModel::getCourses()
        ));
    }

    public function tablaPagos() {
        $this->View->renderJSON(PagosModel::renderPayTable(Request::post('curso'), Request::post('ciclo')));
    }

    public function getTablaPagos() {
        PagosModel::payTable(Request::post('curso'), Request::post('page'));
    }

    public function numeroAlumnos(){
        $cursos   = CursoModel::getCourses();
        $counters = [];
        foreach ($cursos as $curso) {
            $label = 'count_'.$curso->course_id;
            $count = GeneralModel::countByCourse($curso->course_id);
            $counters[$label] = $count;
        }
        $this->View->renderJSON($counters);
    }






    public function obtenerListaPagos() {
        PagosModel::getPaylist(Request::post('lista'), Request::post('page'));
    }

    public function pagarMes(){
        $this->View->renderJSON(
            PagosModel::payMonth(Request::post('student'), Request::post('month'), Request::post('action'))
        );
    }

    public function guardarComentario(){
        $this->View->renderJSON(
            PagosModel::saveComment(Request::post('student'), Request::post('comment'))
        );
    }

    public function pagos() {
        $this->View->render('pagos/pagos', array('years' => PagosModel::getPayYearList()));
    }

    public function mesesCiclo(){
        if (Request::post('ciclo') == 'B') {
            echo date('m') == 1 ? '<option value="ene" selected >Enero</option>'   : '<option value="ene">Enero</option>';
            echo date('m') == 2 ? '<option value="feb" selected >Febrero</option>' : '<option value="feb">Febrero</option>';
            echo date('m') == 3 ? '<option value="mar" selected >Marzo</option>'   : '<option value="mar">Marzo</option>';
            echo date('m') == 4 ? '<option value="abr" selected >Abril</option>'   : '<option value="abr">Abril</option>';
            echo date('m') == 5 ? '<option value="may" selected >Mayo</option>'    : '<option value="may">Mayo</option>';
            echo date('m') == 6 ? '<option value="jun" selected >Junio</option>'   : '<option value="jun">Junio</option>';
            echo date('m') == 7 ? '<option value="jul" selected >Julio</option>'   : '<option value="jul">Julio</option>';
        } else {
            echo date('m') == 8  ? '<option value="ago" selected>Agosto</option>'     : '<option value="ago">Agosto</option>';
            echo date('m') == 9  ? '<option value="sep" selected>Septiembre</option>' : '<option value="sep">Septiembre</option>';
            echo date('m') == 10 ? '<option value="oct" selected>Octubre</option>'    : '<option value="oct">Octubre</option>';
            echo date('m') == 11 ? '<option value="nov" selected>Noviembre</option>'  : '<option value="nov">Noviembre</option>';
            echo date('m') == 12 ? '<option value="dic" selected>Diciembre</option>'  : '<option value="dic">Diciembre</option>';
        }
    }

    public function listaAdeudos() {
        PagosModel::searchPaylist(
                            Request::post('grupo'),
                            Request::post('anio'),
                            Request::post('ciclo'),
                            Request::post('mes'));
    }


}
