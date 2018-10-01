<?php

class MapaController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('js', array('croquis&assets/js'));
    }

    public function index() {
        $this->View->render('mapa/index');
    }

    public function u($user) {
        $this->View->render('mapa/mapa', array('address' => MapaModel::getAddresStudent($user)));
    }


    public function getXMarks(){
        MapaModel::getMarks();
    }

    public function actualizarUbicacion(){
        $this->View->renderJSON(MapaModel::updateStudentLocation(
                                    Request::post('user'),
                                    Request::post('user_type'),
                                    Request::post('street'),
                                    Request::post('numero'),
                                    Request::post('between'),
                                    Request::post('colony'),
                                    Request::post('latitud'),
                                    Request::post('latitud')
        ));
    }

}
