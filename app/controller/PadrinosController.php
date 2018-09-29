<?php

class PadrinosController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        // Registry::set('css',array('jquery.dataTables.min&assets/css', 'maestros&assets/css'));
        Registry::set('js', array('padrinos&assets/js'));
    }

    public function index() {
        $this->View->render('padrinos/index');
    }

    public function padrinos() {
        PadrinosModel::getAllSponsors(Request::post('page'));
    }

    public function nuevoPadrino() {
        if (Request::post('sponsor_name')) {
            $this->View->renderJSON(PadrinosModel::addNewSponsor(
                                                Request::post('sponsor_name'),
                                                Request::post('sponsor_lastname'),
                                                Request::post('sponsor_type'),
                                                Request::post('sponsor_email'),
                                                Request::post('description'),
                                                Request::post('becario')
            ));
        }
    }

    public function obtenerPadrino(){
        if(Request::post('sponsor')){
            $this->View->renderJSON(PadrinosModel::getSponsor(Request::post('sponsor')));
        }
    }

    public function actualizarPadrino() {
        if (Request::post('sponsor_id') && Request::post('edit_name')) {
            $this->View->renderJSON(PadrinosModel::updateSponsor(
                                                Request::post('sponsor_id'),
                                                Request::post('edit_name'),
                                                Request::post('edit_lastname'),
                                                Request::post('edit_type'),
                                                Request::post('edit_email'),
                                                Request::post('edit_description'),
                                                Request::post('edit_becario')
            ));
        } else {
            $this->View->renderJSON(array('success' => false, 'message' => 'Falta información'));
        }
    }

    public function eliminarPadrino(){
        if (Request::post('sponsor')) {
            $this->View->renderJSON(PadrinosModel::deleteSponsor(Request::post('sponsor')));
        } else {
            $this->View->renderJSON(array('success' => false, 'message' => 'Falta información'));
        }
    }

}
