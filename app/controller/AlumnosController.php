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
            'u_type'    => (int)Session::get('user_type'),
            'cursos'    => CursoModel::getCourses()
        ));
    }

    public function getAlumnos() {
        if ((int)Session::get('user_type') === 3) {
            $this->View->renderJSON(AlumnoModel::StudentsClasses(Request::post('curso')));
        } else {
            $this->View->renderJSON(AlumnoModel::Students(Request::post('curso')));
        }
    }

    public function tablaAlumnos() {
        AlumnoModel::tableStudents(Request::post('curso'), Request::post('page'));
    }

    public function cambiarFotoAlumno(){
        if (Request::post('avatar_student') && $_FILES['avatar_file']['tmp_name'] !== "") {
            $avatar = 'student_'.(int)Request::post('avatar_student').'_'.date('his');
            $upload = FotoModel::createAvatar($avatar);
            if ($upload) {
                $this->View->renderJSON(AlumnoModel::createAvatar(Request::post('avatar_student'), $avatar));
            } else {
                $this->View->renderJSON(
                              array('success' => false,
                                    'message' => "Error: Puede que la foto sea muy pequeña, debe ser minimo de 220x220 px")
            );
            }
        } else {
            $this->View->renderJSON(
                            array('success' => false, 
                                  'message' => 'Error falta información para esta acción, intente de nuevo o notifique el error.'));
        }
    }

    public function obtenerGrupos() {
        if(Request::post('curso')){
            $this->View->renderJSON(AlumnoModel::getGroups(Request::post('curso')));
        }
    }

    public function alumnosCurso() {
        if ((int)Session::get('user_type') === 3) {
            $this->View->renderJSON(AlumnoModel::countStudentsByClass());
        } else {
            $this->View->renderJSON(AlumnoModel::countStudents());
        }
    }


    /**
    |===============================================================================================
    | C O N T R O L   D E   A S I G N A C I Ó N   Y   C A M B I O   D E   G R U P O   D E   A L U M N O (S)
    |=============================================================================================== 
    */

    public function cambiarGrupoAlumno(){
        if (Request::post('alumno')) {
            $this->View->renderJSON(AlumnoModel::ChangeStudentGroup(
                                                Request::post('alumno'), 
                                                Request::post('clase'),
                                                Request::post('reinscribir')
            ));
        } else {
            $this->View->renderJSON(array('success' => false, 
                         'message' => '&#x2718; Faltan datos, intente de nuevo o reporte el error!'));
        }
    }

    public function cambiarGrupoAlumnos(){
        if (Request::post('alumnos') && Request::post('clase') !== "") {
            $this->View->renderJSON(AlumnoModel::ChangeStudentsGroup(Request::post('alumnos'), Request::post('clase')));
        } else {
            $this->View->renderJSON(array('success' => false, 
                         'message' => '&#x2718; Faltan datos, intente de nuevo o reporte el error!'));
        }
    }


    /**
    |===============================================================================================
    | C O N T R O L    D E    I N S C R I P C I Ó N    D E    A L U M N O S
    |=============================================================================================== 
    */
   
    public function inscribir() {
        Registry::set('css',array('fileinput.min&assets/libs/css', 'pikaday&assets/libs/css'));
        Registry::set('js', array('mapa&assets/js', 
                                  'fileinput.min&assets/libs/js', 
                                  'moment.min&assets/libs/js', 
                                  'pikaday.min&assets/libs/js',
                                  'inscripcion&assets/js'));
        $this->View->render('alumnos/inscribir', array(
            'cursos'    => CursoModel::getCourses(),
            'niveles'   => CursoModel::getGroups()
        ));
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
                Request::post('fecha_inscripcion'),
                Request::post('f_inicio_alumno')
        ));
    }

    public function crearMapa(){

    }


    /**
    |===============================================================================================
    | P E R F I L    D E L    A L U M N O
    |=============================================================================================== 
    */
   
    public function perfil($alumno) {
        Registry::set('css',array('fileinput.min&assets/libs/css', 'pikaday&assets/libs/css'));
        Registry::set('js', array('fileinput.min&assets/libs/js', 
                                  'moment.min&assets/libs/js', 
                                  'pikaday.min&assets/libs/js', 
                                  'perfil&assets/js'));
        $this->View->render('alumnos/perfil', array(
            'alumno'  => AlumnoModel::studentProfile($alumno),
            'padrinos' => PadrinosModel::getActiveSponsors()
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

    public function actualizarAlumno(){
        if (Request::post('student') && Request::post('name') && Request::post('surname')) {
            $this->View->renderJSON(AlumnoModel::updateStudent(
                                        Request::post('student'),
                                        Request::post('name'),
                                        Request::post('surname'),
                                        Request::post('lastname'),
                                        Request::post('birthday'),
                                        Request::post('genre'),
                                        Request::post('edo_civil'),
                                        Request::post('cellphone'),
                                        Request::post('reference'),
                                        Request::post('sickness'),
                                        Request::post('medication'),
                                        Request::post('homestay'),
                                        Request::post('acta'),
                                        Request::post('invoice'),
                                        Request::post('comment'),
                                        Request::post('address'),
                                        Request::post('street'),
                                        Request::post('number'),
                                        Request::post('between'),
                                        Request::post('colony'),
                                        Request::post('city'),
                                        Request::post('zipcode'),
                                        Request::post('state'),
                                        Request::post('country')
            ));
            exit();
        }

        $this->View->renderJSON(array('success' => false, 'message' => 'Falta información para completar la acción!'));
    }

    public function actualizarTutor(){
        if (Request::post('tutor') && Request::post('name') && Request::post('surname')) {
            $this->View->renderJSON(AlumnoModel::updateTutor(
                                        Request::post('tutor'),
                                        Request::post('name'),
                                        Request::post('surname'),
                                        Request::post('lastname'),
                                        Request::post('ocupation'),
                                        Request::post('relationship'),
                                        Request::post('phone'),
                                        Request::post('cellphone'),
                                        Request::post('relationship_alt'),
                                        Request::post('phone_alt')
            ));

        } else {
            $this->View->renderJSON(array('success' => false, 'message' => 'Falta información para completar la acción!'));
        }
    }

    public function actualizarEstudios(){
        if (Request::post('student')) {
            $clase = null;
            if (Request::post('class') && (int)Request::post('class') !== 0) {
                $clase = (int)Request::post('class');
            }
            $this->View->renderJSON(AlumnoModel::updateStudies(
                                        Request::post('student'),
                                        Request::post('ocupation'),
                                        Request::post('workplace'),
                                        Request::post('studies'),
                                        Request::post('lastgrade'),
                                        Request::post('fecha_inscripcion'),
                                        $clase
            ));
        } else {
            $this->View->renderJSON(array('success' => false, 'message' => 'Falta información para completar la acción!'));
        }
    }



    /**
    |===============================================================================================
    | C O N T R O L    D E    F I R M A S    D E    C O N V E N I O
    |=============================================================================================== 
    */

    public function c($student){
        $this->View->render('alumnos/convenio', array('alumno' => GeneralModel::getStudentDetail($student)));
    }

    public function conveniopdf(){
        $this->View->renderWithoutHeaderAndFooter('alumnos/conveniopdf');
    }


    /**
    |===============================================================================================
    | A L U M N O S    D E B A J A
    |=============================================================================================== 
    */
   
    public function baja() {
        Registry::set('js', array('alumnosbaja&assets/js'));
        $this->View->render('alumnos/baja', array(
            'u_type'    => Session::get('user_type')
        ));
    }

    public function tablaAlumnosBaja(){
        $this->View->renderJSON(AlumnoModel::unsuscribeStudentsTable());
    }

    public function bajaAlumno(){
        $this->View->renderJSON(AlumnoModel::unsuscribeStudent(Request::post('student')));
    }

    public function bajaAlumnos(){
        $this->View->renderJSON(AlumnoModel::unsuscribeStudents(Request::post('students')));
    }

    public function altaAlumno(){
        $this->View->renderJSON(AlumnoModel::suscribeStudent(Request::post('student')));
    }


    /**
    |===============================================================================================
    | A L U M N O S    E L I M I N A D O S
    |=============================================================================================== 
    */

    public function eliminados() {
        Registry::set('js', array('alumnoseliminados&assets/js'));
        $this->View->render('alumnos/eliminados', array(
            'u_type'    => Session::get('user_type')
        ));
    }

    public function tablaAlumnosEliminados(){
        $this->View->renderJSON(AlumnoModel::tableDeletedStudents());
    }

    public function eliminarAlumno() {
        if (Request::post('student')) {
            $this->View->renderJSON(AlumnoModel::deleteStudent(Request::post('student')));
        } else {
            $this->View->renderJSON(
                            array(
                                'success' => false, 
                                'message' => '&#x2718; ERROR:404, intente de nuevo o reporte el error!')
            );
        }
    }

    public function validarClase(){
        $this->View->renderJSON(AlumnoModel::validateClass(Request::post('idClass')));
    }

    public function restaurarAlumno(){
        if (Request::post('idStudent') || Request::post('idClass')) {
            $this->View->renderJSON(AlumnoModel::restoreStudent(Request::post('idStudent'), Request::post('idClass')));
        } else {
            $this->View->renderJSON(
                            array(
                                'success' => false, 
                                'message' => '&#x2718; ERROR:404, intente de nuevo o reporte el error!')
            );
        }
    }

    public function eliminarAlumnos() {
        if (Request::post('students')) {
            $this->View->renderJSON(AlumnoModel::deleteStudents(Request::post('students')));
        } else {
            $this->View->renderJSON(
                            array(
                                'success' => false, 
                                'message' => '&#x2718; No hay alumnos seleccionados, intente de nuevo!')
            );
        }
    }

    /**
    |===============================================================================================
    | R E G I S T R O     D E    I N A S I S T E N C I A
    |=============================================================================================== 
    */

    public function inasistencias() {
        Registry::set('css',array('pikaday&assets/libs/css', 'select2.min&assets/libs/select2'));
        Registry::set('js', array('moment.min&assets/libs/js', 
                                  'pikaday.min&assets/libs/js',
                                  'select2.min&assets/libs/select2', 
                                  'inasistencias&assets/js'));

        $this->View->render('alumnos/inasistencias', array(
            'user_type' => Session::get('user_type'),
            'current'   => Session::get('user_id'),
            'alumnos'   => GeneralModel::getAllStudentsByTeacher(Session::get('user_id'), (int)Session::get('user_type')),
            'maestros'  => GeneralModel::getTeachers()
        ));
    }

    /**
    |===============================================================================================
    | C O N T R O L    D E   F A C T U R A C I Ó N
    |=============================================================================================== 
    */
   
    public function tablaFacturacion() {
        $this->View->renderJSON(AlumnoModel::getInvoiceTable());
    }

    public function obtenerListaFactura() {
        AlumnoModel::getStudentsInvoiceList();
    }

    public function becados() {
        Registry::set('js', array('becados&assets/js'));
        $this->View->render('alumnos/becados', array(
            'becados' => BecasModel::getScholars()
        ));
    }

    public function notas() {
        $this->View->render('alumnos/calificaciones', array(
            'becados' => BecasModel::getScholars()
        ));
    }


    /**
    |===============================================================================================
    | C O N T R O L    D E    A L U M N O S    D E    L A    S E P
    |=============================================================================================== 
    */
    public function sep() {
        $this->View->render('alumnos/sep', array(
            'students' => GeneralModel::prospectSepStudents(),
        ));
    }

    public function egresados() {
        Registry::set('js', array('alumnosegresados&assets/js'));
        $this->View->render('alumnos/egresados', array(
            'user_name' => Session::get('user_name'),
        ));
    }





    public function editUsername() {
        $this->View->render('user/editUsername');
    }


    /////////////////////////////////////////////
    // =  =   =   =  =  M I S C =  =  =  =  =  //
    /////////////////////////////////////////////



    public function backupDatabase() {
        GeneralModel::createBackupDatabase();
    }

}
