<?php

class AlumnosController extends Controller
{

    public function __construct() {
        parent::__construct();
        Auth::checkAuthentication();

        // Registry::set('css',array('fileinput.min&assets/css','icons&assets/css','mapa&assets/css','alumnos&assets/css'));
        Registry::set('css',array('fileinput.min&assets/libs/css'));
        Registry::set('js', array('fileinput.min&assets/libs/js', 'alumnos&assets/js'));
    }

    public function index() {
        $this->View->render('alumnos/index', array(
            'u_type'    => Session::get('user_account_type'),
            'cursos'    => CursoModel::getCourses()
        ));
    }


    public function tablaAlumnos() {
        AlumnoModel::tableStudents(Request::post('curso'), Request::post('page'));
    }

    public function obtenerGrupos() {
        if(Request::post('curso')){
            $this->View->renderJSON(AlumnoModel::getGroupsByCourse(Request::post('curso')));
        }
    }

    public function alumnosCurso() {
        $this->View->renderJSON(AlumnoModel::countStudents());
    }

    public function perfil($alumno) {
        Registry::set('js', array('mapa&assets/js', 'perfil&assets/js'));
        $this->View->render('alumnos/perfil', array(
            'alumno'  => AlumnoModel::studentProfile($alumno)
        ));
    }

    public function editarAlumno(){
        switch ((int)Request::post('form')) {
            case 1:
                $this->View->renderWithoutHeaderAndFooter('alumnos/editar/editar_alumno', array(
                    'alumno' => AlumnoModel::studentProfileData(Request::post('alumno'))
                ));
                break;
            case 2:
                $this->View->renderWithoutHeaderAndFooter('alumnos/editar/editar_tutor', array(
                    'tutor' => AlumnoModel::tutorProfileData(Request::post('tutor'))
                ));
                break;
            case 3:
                $this->View->renderWithoutHeaderAndFooter('alumnos/editar/editar_estudios', array(
                    'estudios' => AlumnoModel::studiesProfileData(Request::post('alumno')),
                    'cursos'   => CursoModel::getActiveCourses(),
                    'grupos'   => CursoModel::getActiveGroups()
                ));
                break;
            default:
                $this->View->renderWithoutHeaderAndFooter('alumnos/editar/editar_alumno', array(
                    'alumno' => AlumnoModel::studentProfileData(Request::post('alumno'))
                ));
                break;
        }
    }


    public function formularioInscripcion(){

        switch (Request::get('formulario')) {
            case 'tutor':
                $this->View->renderWithoutHeaderAndFooter('alumnos/inscripcion/form_tutor');
                break;
            case 'alumno':
                $this->View->renderWithoutHeaderAndFooter('alumnos/inscripcion/form_alumno');
                break;
            case 'estudios':
                $this->View->renderWithoutHeaderAndFooter('alumnos/inscripcion/form_estudios', 
                                                                array('cursos' => CursoModel::getCourses()));
                break;
            default:
                $this->View->renderWithoutHeaderAndFooter('alumnos/inscripcion/form_tutor');
                break;
        }
    }

    public function validarTutor() {
        $this->View->renderJSON(AlumnoModel::tutorExist(
                                    Request::post('name'),
                                    Request::post('surname'),
                                    Request::post('lastname')
        ));
    }

    //Obtener los datos de un tutor dado
    public function obtenerDatosTutor() {
        $this->View->renderJSON(AlumnoModel::getTutorByID(
                                    Request::post('tutor')
        ));
    }

    public function validarAlumno() {
        $this->View->renderJSON(AlumnoModel::studentExist(
                                    Request::post('name'),
                                    Request::post('surname'),
                                    Request::post('lastname')
        ));
    }


    public function gruposPorNivel() {
        if(Request::post('curso')){
            $this->View->renderJSON(AlumnoModel::getGroups(Request::post('curso')));
        }
    }

    public function datosClase() {
        $this->View->renderJSON(CursoModel::classData(Request::post('clase')));
    }

    // Guardar Informacion del tutor.
    public function crearTutor(){
        $this->View->renderJSON(AlumnoModel::createTutor(
                                    trim(Request::post('nombre_tutor')),
                                    trim(Request::post('apellido_pat')),
                                    trim(Request::post('apellido_mat')),
                                    Request::post('parentesco'),
                                    trim(Request::post('ocupacion')),
                                    trim(Request::post('tel_celular')),
                                    trim(Request::post('tel_casa')),
                                    trim(Request::post('tel_alterno')),
                                    Request::post('parentesco_alterno'),
                                    trim(Request::post('calle')),
                                    trim(Request::post('numero')),
                                    trim(Request::post('entre')),
                                    trim(Request::post('colonia'))
        ));
    }

    public function crearAlumno(){
        $tutor   = null;
        $address = null;
        $birthday = null;
        $age      = null;

        if (trim(Request::post('tutor')) && trim(Request::post('address'))) {
            $tutor   = trim(Request::post('tutor'));
            $address = trim(Request::post('address')); 
        }

        if(trim(Request::post('year')) && trim(Request::post('month')) && trim(Request::post('day'))){
            $birthday = trim(Request::post('year')).'-'.
                        trim(Request::post('month')).'-'.
                        trim(Request::post('day'));
            $age = H::getAge($birthday);
        }

        $this->View->renderJSON(AlumnoModel::createStudent(
                                    $tutor,
                                    $address,
                                    trim(Request::post('surname')), 
                                    trim(Request::post('lastname')), 
                                    trim(Request::post('name')), 
                                    $birthday,
                                    $age,
                                    Request::post('genero'),
                                    Request::post('edo_civil'),
                                    trim(Request::post('celular')),
                                    trim(Request::post('referencia')),
                                    trim(Request::post('padecimiento')),
                                    trim(Request::post('tratamiento')),
                                    Request::post('comentario'),
                                    Request::post('facturacion'), 
                                    Request::post('homestay'),
                                    Request::post('acta'),
                                    trim(Request::post('street_s')), 
                                    trim(Request::post('number_s')), 
                                    trim(Request::post('between_s')),
                                    trim(Request::post('colony_s'))
        ));
    }

    public function crearAvatar(){
        $response = array('success' => false, 'message' => 'No se guardo la foto del alumno, intentelo mas tarde.');
        if (Request::post('student')) {
            $avatar = Request::post('genre');
            if ($_FILES['avatar_file']['tmp_name'] !== "") {
                $avatar = 'student_'.(int)Request::post('student');
                $upload = FotoModel::createAvatar($avatar);
                if (!$upload) {
                    $avatar = Request::post('genre');
                }
            }

            $response = AlumnoModel::createAvatar(Request::post('student'), $avatar);
        }

        $this->View->renderJSON($response);
    }

    public function crearEstudios(){
        $grupo = null;
        if (Request::post('grupo') && (int)Request::post('grupo') !== 0) {
            $grupo = (int)Request::post('grupo');
        }

        $this->View->renderJSON(AlumnoModel::createStudies(
                (int)Request::post('alumno'),
                Request::post('ocupacion'),
                Request::post('lugar_trabajo'),
                Request::post('nivel_estudio'),
                Request::post('grado_estudio'),
                Request::post('curso_previo'),
                Request::post('description_previo'),
                $grupo,
                Request::post('f_inicio_alumno')
        ));
    }

    public function crearMapa(){

    }





    public function inscribir() {
        // Session::destroy('activo');
        if (Session::get('activo') == null) {
            Session::set('activo', 'info_tutor');
        }
        Registry::set('css',array('fileinput.min&assets/libs/css', 'tpicker.min&assets/libs/css'));
    Registry::set('js', array('mapa&assets/js', 'fileinput.min&assets/libs/js', 'moment.min&assets/libs/js', 'tpicker&assets/libs/js', 'inscripcion&assets/js'));
        $this->View->render('alumnos/inscribir', array(
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getGroups()
        ));
    }

    public function obtenerAlumnos() {
        AlumnoModel::students(Request::post('curso'));
    }

    //Comprobar si existe el alumno en la BD
    public function existeAlumno() {
        echo json_encode(AlumnoModel::studentExist(
            Request::post('name'),
            Request::post('surname'),
            Request::post('lastname')
        ));
    }

    public function obtenerNivelesCurso() {
        if(Request::post('curso')){
            echo json_encode(AlumnoModel::getLevelsByClass(Request::post('curso')));
        }
    }

    public function obtenerDiasClase() {
        if(Request::post('clase')){
            echo json_encode(CursoModel::getDaysByClass(Request::post('clase')));
        }
    }

    public function actualizarDatosAlumno(){
        $alumno = Request::post('student_id');

        if (Request::post('student_id') && Request::post('name') && Request::post('surname') && Request::post('lastname') && Request::post('genre') && Request::post('edo_civil')) {
            AlumnoModel::updateStudentData(
                Request::post('student_id'),
                Request::post('tutor_id'),
                Request::post('name'),
                Request::post('surname'),
                Request::post('lastname'),
                Request::post('birthdate'),
                Request::post('genre'),
                Request::post('edo_civil'),
                Request::post('cellphone'),
                Request::post('reference'),
                Request::post('street'),
                Request::post('number'),
                Request::post('between'),
                Request::post('colony'),
                Request::post('sickness'),
                Request::post('medication'),
                Request::post('homestay'),
                Request::post('acta'),
                Request::post('invoice'),
                Request::post('comment')
            );
            Redirect::to('alumnos/perfilAlumno/'.$alumno);
        } else {
            Session::add('feedback_negative', "Falta información para completar el proceso");
            Redirect::to('alumnos/perfilAlumno/'.$alumno);
        }
    }

    public function obtenerAlumnosBaja() {
        AlumnoModel::getStudentsCheckout();
    }

    public function convenio(){
        $this->View->render('alumnos/convenio');
    }

    public function conveniopdf(){
        $this->View->renderWithoutHeaderAndFooter('alumnos/conveniopdf');
    }

    public function bajaAlumno(){
        AlumnoModel::checkOutStudent(Request::post('alumno'), Request::post('estado'));
    }

    public function actualizarDatosTutor(){

        if (Request::post('id_tutor') && Request::post('nombre_tutor') && Request::post('ape_pat') && Request::post('ape_mat')) {
            AlumnoModel::updateTutorData(
                Request::post('id_tutor'),
                Request::post('nombre_tutor'),
                Request::post('ape_pat'),
                Request::post('ape_mat'),
                Request::post('ocupacion'),
                Request::post('parentesco'),
                Request::post('tel_casa'),
                Request::post('tel_celular'),
                Request::post('familiar'),
                Request::post('tel_familiar'));
            Redirect::to('alumnos/perfilAlumno/'.Request::post('alumno'));
        }  else {
            Session::add('feedback_negative', "Falta información para completar el proceso");
            Redirect::to('alumnos/perfilAlumno/'.Request::post('alumno'));
        }
    }

    public function actualizarDatosAcademicos(){
        if (Request::post('alumno')) {
            AlumnoModel::updateAcademicData(
                Request::post('alumno'),
                Request::post('ocupacion'),
                Request::post('lugar_trabajo'),
                Request::post('nivel_estudio'),
                Request::post('grado_estudio')
                );
            Redirect::to('alumnos/perfilAlumno/'.Request::post('alumno'));
        } else {
            Session::add('feedback_negative', "Falta información para completar el proceso");
            Redirect::to('alumnos/perfilAlumno/'.Request::post('alumno'));
        }
    }

    public function agregarAlumnoGrupo(){
        if (Request::post('alumno') && Request::post('clase')) {
            AlumnoModel::AddStudentToClass(Request::post('alumno'), Request::post('clase'));
        } else {
            echo 0;
        }
    }

    public function cambiarGrupoAlumno(){
        if (Request::post('alumno') && Request::post('clase') !== "") {
            AlumnoModel::ChangeStudentGroup(Request::post('alumno'), Request::post('clase'));
        } else {
            echo 0;
        }
    }

    public function cambiarGrupoAlumnos(){
        if (Request::post('alumnos') && Request::post('clase') !== "") {
            AlumnoModel::ChangeStudentsGroup(Request::post('alumnos'), Request::post('clase'));
        } else {
            echo 0;
        }
    }

    public function obtenerListaFactura() {
        AlumnoModel::getStudentsInvoiceList();
    }

    public function becados() {
        $this->View->render('alumnos/becados', array(
            'user_name' => Session::get('user_name'),
        ));
    }

    public function notas() {
        $this->View->render('alumnos/calificaciones', array(
            'user_name' => Session::get('user_name'),
        ));
    }

    public function sep() {
        $this->View->render('alumnos/sep', array(
            'user_name' => Session::get('user_name'),
        ));
    }

    public function egresados() {
        $this->View->render('alumnos/egresados', array(
            'user_name' => Session::get('user_name'),
        ));
    }

    public function baja() {
        Registry::set('js', array('alumnosbaja&assets/js'));
        $this->View->render('alumnos/baja', array(
            'u_type'    => Session::get('user_account_type')
        ));
    }

    public function tablaAlumnosBaja(){
        $this->View->renderJSON(AlumnoModel::tableInactiveStudents());
    }

    public function eliminados() {
        Registry::set('js', array('alumnoseliminados&assets/js'));
        $this->View->render('alumnos/eliminados', array(
            'u_type'    => Session::get('user_account_type')
        ));
    }

    public function tablaAlumnosEliminados(){
        $this->View->renderJSON(AlumnoModel::tableDeletedStudents());
    }

    public function editUsername() {
        $this->View->render('user/editUsername');
    }

    public function eliminarAlumno() {
        if (Request::post('alumno')) {
            echo json_encode(AlumnoModel::deleteStudent(Request::post('alumno')));
        }
    }

    public function eliminarAlumnos() {
        if (Request::post('alumnos')) {
            echo json_encode(AlumnoModel::deleteStudents((array)Request::post('alumnos')));
        }
    }





    /////////////////////////////////////////////
    // =  =   =   =  =  M I S C =  =  =  =  =  //
    /////////////////////////////////////////////



    public function backupDatabase() {
        GeneralModel::createBackupDatabase();
    }

}
