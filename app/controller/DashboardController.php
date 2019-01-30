<?php

/**
 * This controller shows an area that's only visible for logged in users (because of Auth::checkAuthentication(); in line 16)
 */
class DashboardController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
        Auth::checkAuthentication();
        Registry::set('css',array('fullcalendar.min&assets/libs/css'));
        Registry::set('js', array('moment.min&assets/libs/js', 'fullcalendar.min&assets/libs/js', 'locale-all&assets/libs/js','dashboard&assets/js'));
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function index(){
        $this->View->render('dashboard/index');
    }

    public function admin(){
        $this->View->render('dashboard/admin', array(
            'backup' => GeneralModel::getLastBackup()
        ));
    }

    public function nuevaTabla(){
        $this->View->renderJSON(GeneralModel::createTable());
    }

    public function limpiarDB(){
        $this->View->renderJSON(GeneralModel::cleanDatabase());
    }

    public function cargarDB(){
        $this->View->renderJSON(GeneralModel::feedDatabase());
    }

    public function eventos(){
        $this->View->renderJSON(DashboardModel::calendarEvents());
    }

    // Funciona-->
    public function backDatabase() {
        GeneralModel::createBackupDatabase();
    }

    public function backupDatabase() {
        $this->View->renderJSON(GeneralModel::makeBackupDatabase());
    }

    public function importDatabase() {
        $this->View->renderJSON(GeneralModel::restoreDatabase());
    }

    public function descargarDB($data=null) {

        GeneralModel::createBackupDatabase();

        $database = GeneralModel::getLastBackup();

        if ($database !== null) {
            $file_url = Config::get('PATH_BACKUPS'). $database;
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary"); 
            header("Content-disposition: attachment; filename=\"" . basename($file_url) .     "\""); 
            readfile($file_url); 
            exit;
        }

        dump('Nada para descargar...');
    }
}
