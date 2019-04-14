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
        // $this->View->render('pagos/index', array(
        //     'cursos' => CursoModel::getCourses()
        // ));

        if ((int)date('m') < 8) {
            $this->View->render('pagos/pagos_b', array(
                'cursos' => CursoModel::getCourses()
            ));
        } else {
            $this->View->render('pagos/pagos_a', array(
                'cursos' => CursoModel::getCourses()
            ));
        }
    }

    public function tablaPagos() {
        $this->View->renderJSON(PagosModel::renderPayTable(Request::post('curso'), Request::post('ciclo')));
    }

    public function numeroAlumnos(){
        $cursos   = CursoModel::getCourses();
        $counters = [];
        $total = 0;
        foreach ($cursos as $curso) {
            $label = 'count_'.$curso->course_id;
            $count = GeneralModel::countByCourse($curso->course_id);
            $total += $count;
            $counters[$label] = $count;
        }
        $counters['count_all'] = $total;
        $this->View->renderJSON($counters);
    }


    public function pagos() {
        $this->View->render('pagos/pagos', array(
            'cursos' => CursoModel::getCourses()
        ));
    }

    public function tablaPagosFull() {
        $this->View->renderJSON(PagosModel::renderFullPayTable(Request::post('curso')));
    }

    public function infoPago() {
        $this->View->renderJSON(PagosModel::getRelatedInfo(Request::post('alumno'), Request::post('tutor')));
    }

    public function actualizar_costos(){
        if (!Request::post('course_id')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'No se encuentra el curso seleccionado'));
            return;
        }

        if (!Request::post('costo_normal')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'Especifique el costo normal del curso'));
            return;
        }

        if (!Request::post('costo_descuento')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'Especifique el costo con descuento del curso'));
            return;
        }

        $this->View->renderJSON(
            CursoModel::updateCoursePrice(Request::post('course_id'), Request::post('costo_normal'), Request::post('costo_descuento'))
        );
    }






    public function obtenerListaPagos() {
        PagosModel::getPaylist(Request::post('lista'), Request::post('page'));
    }

    public function pagarMes(){
        $this->View->renderJSON(
            PagosModel::payMonth(Request::post('student'), Request::post('month'), Request::post('action'))
        );
    }

    public function pagarMensualidad(){
        if (!Request::post('monthToPay')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'Especifique mes a pagar'));
            return;
        }

        if (!Request::post('payStatus')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'Especifique si va realizar un pago o no'));
            return;
        }
        if (!Request::post('payStudent')) {
            $this->View->renderJSON(array('success' => false, 
                                          'messsage' => 'Error desconocido: reporte problema (CODE: empty_st) '));
            return;
        }

        $this->View->renderJSON(PagosModel::payMonthly(
                                    Request::post('payStudent'), 
                                    Request::post('monthToPay'), 
                                    Request::post('payStatus'),
                                    Request::post('payComment')
        ));
    }

    public function pagoMensualidad(){
        if (!Request::post('monthToPay')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'Especifique mes a pagar'));
            return;
        }

        if (!Request::post('payStatus')) {
            $this->View->renderJSON(array('success' => false, 'messsage' => 'Especifique si va realizar un pago o no'));
            return;
        }
        if (!Request::post('payStudent')) {
            $this->View->renderJSON(array('success' => false, 
                                          'messsage' => 'Error desconocido: reporte problema (CODE: empty_st) '));
            return;
        }

        $familiares = [];
        if ((array)Request::post('familiares')) {
            $familiares = (array)Request::post('familiares');
        }

        $this->View->renderJSON(PagosModel::savePayMonthly(
                                    Request::post('payStudent'), 
                                    Request::post('monthToPay'), 
                                    Request::post('payStatus'),
                                    Request::post('payComment'),
                                    $familiares
        ));
    }

    public function guardarComentario(){
        $this->View->renderJSON(
            PagosModel::saveComment(Request::post('student'), Request::post('comment'))
        );
    }

    public function guardarEstado(){
        $this->View->renderJSON(
            PagosModel::saveStatus(Request::post('student_id'), Request::post('status'))
        );
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
}
