<?php

class WinesController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        Registry::set('js', array('wines&assets/js'));
    }

    public function index() {
        $this->View->render('misc/index', array(
            'tipos' => WinesModel::getTiposWine()
        ));
    }

    public function wineList(){
        WinesModel::getWineList();
    }

    public function wineTypes(){
        WinesModel::getWineTipos(Request::post('tipo'), Request::post('page'));
    }

    public function saveWine(){
        $this->View->renderJSON(WinesModel::createWine(
            Request::post('nombre'),
            Request::post('precio'),
            Request::post('costo'),
            Request::post('tipo'),
            Request::post('pais'),
            Request::post('especs')));
    }

    public function updateWine(){
        $this->View->renderJSON(WinesModel::updateWine(
            Request::post('vino'),
            Request::post('precio'),
            Request::post('costo')));
    }

}
