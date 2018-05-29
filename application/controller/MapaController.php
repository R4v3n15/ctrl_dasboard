<?php

class MapaController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('js', array('wines&assets/js'));
    }

    public function index() {
        $this->View->render('mapa/index');
    }

    public function u($user) {
        $this->View->render('mapa/mapa', array('alumno' => $user));
    }


    public function getXMarks(){
        MapaModel::getMarks();
    }

}
