<?php

class AlumnoModel
{
    public static function Students($course){
        $user_type = (int)Session::get('user_type');
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = null;

        // Obtener Lista de Alumnos
        switch ($course) {
            case 'all': 
                    $students = GeneralModel::allStudents(); break;
            case 'standby': 
                    $students = GeneralModel::standbyStudents(); break;
            default: 
                    $curso = (int)$course;
                    $students = GeneralModel::studentsByCourse($curso); break;
        }

        // Si existe al menos 1, se procesa y pasa a la vista
        $datos = [];
        if ($students !== null) {
            $counter = 1;
            foreach ($students as $alumno) {
                $id_grupo = 0;
                $nombre_curso = '';
                $maestro = '- - - -';
                $horario = '- - - -';
                $dias = '';
                $finished = false;
                if ($user_type !== 3) {
                    $grupo = '<a href="javascript:void(0)" 
                                 class="link btnSetGroup badge badge-warning" 
                                 data-student="'.$alumno->student_id.'"
                                 title="Agregar grupo">Agregar a Grupo</a>';
                } else {
                    $grupo = '<a href="javascript:void(0)" 
                             class="link badge badge-warning"
                             title="Grupo">En Espera</a>';
                }

                if ($alumno->class_id !== NULL) {
                    $clase = $database->prepare("SELECT c.class_id, c.course_id, c.teacher_id, c.schedul_id, c.status, c.book,
                                                        cu.course, g.group_name, h.date_end, h.hour_init, h.hour_end
                                                 FROM classes as c, courses as cu, groups as g, schedules as h
                                                 WHERE c.class_id   = :clase
                                                   AND c.course_id  = cu.course_id
                                                   AND c.group_id   = g.group_id
                                                   AND c.schedul_id = h.schedul_id
                                                 LIMIT 1;");
                    $clase->execute(array(':clase' => $alumno->class_id));

                    if ($clase->rowCount() > 0) {
                        $clase = $clase->fetch();
                        $id_grupo = $clase->class_id;
                        $nombre_curso = ucwords(strtolower($clase->course));
                        $finished = strtotime(date('Y-m-d')) > strtotime($clase->date_end . ' + 5 days');

                        $getDays = $database->prepare("SELECT d.day 
                                                       FROM days as d, schedul_days as sd 
                                                       WHERE sd.schedul_id = :schedul
                                                         AND sd.day_id     = d.day_id
                                                       ORDER BY d.day_id;");
                        $getDays->execute(array(':schedul' => $clase->schedul_id));
                        $days = $getDays->fetchAll();

                        $pointer = 1;
                        foreach ($days as $day) {
                            if(count($days) > $pointer) {
                                $dias .= ucwords(strtolower($day->day)) . ', ';
                            } else {
                                $dias .= ucwords(strtolower($day->day));
                            }
                            $pointer++;
                        }


                        $horario  = date('g:i a', strtotime($clase->hour_init)) . ' - ' . date('g:i a', strtotime($clase->hour_end));
                        if ($user_type !== 3) {
                        $grupo = '<a class="btnChangeGroup"
                                     href="javascript:void(0)"
                                     data-student="'.$alumno->student_id.'"
                                     data-class="'.$clase->class_id.'"
                                     data-course="'.$clase->course_id.'"
                                     data-group="'.$nombre_curso.' '.$clase->group_name.'"
                                     data-reinscripcion="'.$finished.'"
                                     title="Agregar grupo">'.$nombre_curso.' '.$clase->group_name.'</a> <br>'.$clase->book;
                        } else {
                           $grupo = '<a href="javascript:void(0)" title="Grupo">'.$nombre_curso.' '.$clase->group_name.'</a>'; 
                        }

                        if ($clase->teacher_id !== null) {
                            $getUser = $database->prepare("SELECT name, lastname FROM users WHERE user_id = :teacher LIMIT 1;");
                            $getUser->execute(array(':teacher' => $clase->teacher_id));

                            if ($getUser->rowCount() > 0) {
                                $getUser = $getUser->fetch();
                                $maestro = $getUser->name . ' ' . $getUser->lastname;
                            }
                        }
                    }
                }

                //-> Tutor del Alumno
                $id_tutor     = 0;
                $nombre_tutor = '- - - -';
                if ($alumno->id_tutor !== NULL) {
                    $tutor = $database->prepare("SELECT id_tutor, namet, surnamet, lastnamet
                                                    FROM tutors
                                                    WHERE id_tutor = :tutor
                                                 LIMIT 1;");
                    $tutor->execute(array(':tutor' => $alumno->id_tutor));
                    if ($tutor->rowCount() > 0) {
                        $tutor = $tutor->fetch();
                        $id_tutor = $tutor->id_tutor;
                        $nombre_tutor = $tutor->namet.' '.$tutor->surnamet;
                    }
                }

                $avatar = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').$alumno->avatar.'.jpg';
                $realPath = Config::get('PATH_AVATARS_STUDENTS').$alumno->avatar.'.jpg';
                if (!file_exists($realPath)) {
                    $avatar = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').strtolower($alumno->genre).'.jpg';
                }
                
                $convenio = $alumno->convenio == "0" ?
                                '<i data-toggle="tooltip" title="Convenio Pendiente" class="fa fa-file"></i>' : '';
                            
                $check = '<div class="custom-control custom-checkbox">
                            <input type="checkbox" 
                                   class="custom-control-input check-item" 
                                   id="customCheck'.$counter.'"
                                   data-inscribir="'.($finished ? 1 : 0).'" 
                                   value="'.$alumno->student_id.'">
                            <label class="custom-control-label" for="customCheck'.$counter.'">'.$counter.'</label>
                        </div>';
                            
                if ($user_type !== 3) {
                $photo = '<img class="rounded-circle btnChangeAvatar" 
                               src="'.$avatar.'"
                               data-student="'.$alumno->student_id.'" 
                               alt="foto" 
                               widt="42" 
                               height="42">';
                } else {
                $photo = '<img class="rounded-circle" src="'.$avatar.'" alt="foto" widt="42" height="42">';
                }

                $optClass = $finished ? 'danger' : 'primary';

                $options = '<a href="javascript:void(0)"
                         data-target="#"
                         class="btn btn-outline-'.$optClass.' btn-sm dropdown-toggle"
                         data-toggle="dropdown">Más.. <span class="caret"></span>
                      </a>';
                $options .= '<ul class="dropdown-menu student">';
                if ($user_type !== 3) {
                $options .= '<li>
                            <a href="'.Config::get('URL').'alumnos/perfil/'.$alumno->student_id.'"
                               data-student="'.$alumno->student_id.'"
                               data-tutor="'.$id_tutor.'"
                               data-clase="'.$id_grupo.'"
                               data-curso="'.$nombre_curso.'">
                                <i class="ml-1 text-dark fa fa-chevron-right"></i>
                                Perfil
                            </a>
                        </li>';
                $options .=    '<li>
                            <a href="'.Config::get('URL').'alumnos/c/'.$alumno->student_id.'">
                                <i class="ml-1 text-primary fa fa-chevron-right"></i>
                                Convenio
                            </a></li>';
                $options .=    '<li>
                            <a href="javascript:void(0)"
                               class="btnChangeAvatar"
                               data-student="'.$alumno->student_id.'">
                                <i class="ml-1 text-info fa fa-chevron-right"></i>
                                Cambiar Foto
                            </a>
                        </li>';
                }
                $options .=   '<li>
                            <a href="'.Config::get('URL').'evaluaciones/st/'.$alumno->student_id.'">
                                <i class="ml-1 text-success fa fa-chevron-right"></i>
                                Calificaciones
                            </a>
                        </li>';
                if ($user_type !== 3) {
                $options .=   '<li>
                            <a href="'.Config::get('URL').'mapa/u/'.$alumno->student_id.'">
                                <i class="ml-1 text-dark fa fa-chevron-right"></i>
                                Croquis
                            </a>
                        </li>';
                $options .=   '<li>
                            <a  href="javascript:void(0)" 
                                class="btnUnsuscribeStudent" 
                                data-student="'.$alumno->student_id.'"
                                data-name="'.$alumno->name.' '.$alumno->surname.'">
                                <i class="ml-1 text-warning fa fa-chevron-right"></i>
                                Dar de Baja
                            </a>
                        </li>';
                $options .=   '<li>
                            <a  href="javascript:void(0)" 
                                class="btnDeleteStudent" 
                                data-student="'.$alumno->student_id.'"
                                data-name="'.$alumno->name.' '.$alumno->surname.'">
                                <i class="ml-1 text-danger fa fa-chevron-right"></i>
                                Eliminar
                            </a>
                        </li>';
                }
                $options .= '</ul>';

                $dias = '<small>'.$dias.'</small>';

                $info = array(
                    'count'      => $check,
                    'avatar'     => $photo,
                    'surname'    => $alumno->surname.' '.$alumno->lastname,
                    'name'       => $alumno->name,
                    'studies'    => $alumno->studies.' '.$alumno->lastgrade,
                    'age'        => $alumno->age,
                    'group_name' => $grupo,
                    'teacher'    => ucwords(strtolower($maestro)),
                    'horary'     => $dias . '<br>' . $horario,
                    'options'    => $options,
                    'finished'   => $finished
                );

                array_push($datos, $info);
                $counter++;
            }
        }

        return array('data' => $datos);   
    }

    // Numero de alumno por grupo
    public static function countStudents(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $courses =  $database->prepare("SELECT course_id FROM courses WHERE status = 1;");
        $courses->execute();

        // Total de alumnos
        $all = GeneralModel::countAll();
        // Solo alumnos en espera
        $standby = GeneralModel::countStandby();

        $counters = array('count_all' => $all, 'count_standby' => $standby);

        // Alumnos por cada curso que exista
        if($courses->rowCount() > 0){
            $cursos = $courses->fetchAll();
            foreach ($cursos as $curso) {
                $label = 'count_'.$curso->course_id;
                $count = GeneralModel::countByCourse($curso->course_id);
                $counters[$label] = $count;
            }
        }

        return $counters;
    }

    public static function getGroups($course) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $fecha = H::getTime('Y-m-d');

        $_sql = $database->prepare("SELECT c.class_id, c.group_id, g.group_name
					                FROM classes as c, groups as g, schedules as h
					                WHERE c.course_id = :course
					                  AND c.group_id  = g.group_id
                                      AND c.schedul_id = h.schedul_id
					                  AND c.status = 1
                                      AND h.date_end > :today;");
        $_sql->execute(array(':today' => $fecha, ':course' => $course));

        if($_sql->rowCount() > 0){
            return $_sql->fetchAll();
        }
        return null;
    }


    /**
    |===============================================================================================
    | S T U D E N T    F O R    T E A C H E R S
    |=============================================================================================== 
    */
   
    public static function StudentsClasses($course){
        $user_type = (int)Session::get('user_type');
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = null;

        // Obtener Lista de Alumnos
        $curso = (int)$course;
        $students = GeneralModel::studentsByTeacher($curso, Session::get('user_id'));

        // Si existe al menos 1, se procesa y pasa a la vista
        $datos = [];
        if ($students !== null) {
            $counter = 1;
            foreach ($students as $alumno) {
                $id_grupo = 0;
                $nombre_curso = '';
                $maestro = '- - - -';
                $horario = '- - - -';
                $dias = '';
                $finished = false;
                if ($user_type !== 3) {
                    $grupo = '<a href="javascript:void(0)" 
                                 class="link btnSetGroup badge badge-warning" 
                                 data-student="'.$alumno->student_id.'"
                                 title="Agregar grupo">Agregar a Grupo</a>';
                } else {
                    $grupo = '<a href="javascript:void(0)" 
                             class="link badge badge-warning"
                             title="Grupo">En Espera</a>';
                }

                if ($alumno->class_id !== NULL) {
                    $clase = $database->prepare("SELECT c.class_id, c.course_id, c.teacher_id, c.schedul_id, c.status, c.book,
                                                        cu.course, g.group_name, h.date_end, h.hour_init, h.hour_end
                                                 FROM classes as c, courses as cu, groups as g, schedules as h
                                                 WHERE c.class_id   = :clase
                                                   AND c.course_id  = cu.course_id
                                                   AND c.group_id   = g.group_id
                                                   AND c.schedul_id = h.schedul_id
                                                 LIMIT 1;");
                    $clase->execute(array(':clase' => $alumno->class_id));

                    if ($clase->rowCount() > 0) {
                        $clase = $clase->fetch();
                        $id_grupo = $clase->class_id;
                        $nombre_curso = ucwords(strtolower($clase->course));
                        $finished = strtotime(date('Y-m-d')) > strtotime($clase->date_end . ' + 5 days');

                        $getDays = $database->prepare("SELECT d.day 
                                                       FROM days as d, schedul_days as sd 
                                                       WHERE sd.schedul_id = :schedul
                                                         AND sd.day_id     = d.day_id
                                                       ORDER BY d.day_id;");
                        $getDays->execute(array(':schedul' => $clase->schedul_id));
                        $days = $getDays->fetchAll();

                        $pointer = 1;
                        foreach ($days as $day) {
                            if(count($days) > $pointer) {
                                $dias .= ucwords(strtolower($day->day)) . ', ';
                            } else {
                                $dias .= ucwords(strtolower($day->day));
                            }
                            $pointer++;
                        }


                        $libro = $clase->book ? '<br>Libro: '.$clase->book : '';
                        $horario  = date('g:i a', strtotime($clase->hour_init)) . ' - ' . date('g:i a', strtotime($clase->hour_end));
                        if ($user_type !== 3) {
                        $grupo = '<a class="btnChangeGroup"
                                     href="javascript:void(0)"
                                     data-student="'.$alumno->student_id.'"
                                     data-class="'.$clase->class_id.'"
                                     data-course="'.$clase->course_id.'"
                                     data-group="'.$nombre_curso.' '.$clase->group_name.'"
                                     data-reinscripcion="'.$finished.'"
                                     title="Agregar grupo">'.$nombre_curso.' '.$clase->group_name.'</a>'.$libro;
                        } else {
                           $grupo = '<a href="javascript:void(0)" title="Grupo">'.$nombre_curso.' '.$clase->group_name.'</a>'.$libro; 
                        }

                        if ($clase->teacher_id !== null) {
                            $getUser = $database->prepare("SELECT name, lastname FROM users WHERE user_id = :teacher LIMIT 1;");
                            $getUser->execute(array(':teacher' => $clase->teacher_id));

                            if ($getUser->rowCount() > 0) {
                                $getUser = $getUser->fetch();
                                $maestro = $getUser->name . ' ' . $getUser->lastname;
                            }
                        }
                    }
                }

                // BECARIOS
                $getBeca = $database->prepare("SELECT * FROM becas WHERE student_id = :student AND status = 1 LIMIT 1;");
                $getBeca->execute(array('student' => $alumno->student_id));

                $becado = '';
                if($getBeca->rowCount() > 0){
                    $becado = 'Becario';
                }

                //-> Tutor del Alumno
                $id_tutor     = 0;
                $nombre_tutor = '- - - -';
                if ($alumno->id_tutor !== NULL) {
                    $tutor = $database->prepare("SELECT id_tutor, namet, surnamet, lastnamet
                                                    FROM tutors
                                                    WHERE id_tutor = :tutor
                                                 LIMIT 1;");
                    $tutor->execute(array(':tutor' => $alumno->id_tutor));
                    if ($tutor->rowCount() > 0) {
                        $tutor = $tutor->fetch();
                        $id_tutor = $tutor->id_tutor;
                        $nombre_tutor = $tutor->namet.' '.$tutor->surnamet;
                    }
                }

                $avatar = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').$alumno->avatar.'.jpg';
                $realPath = Config::get('PATH_AVATARS_STUDENTS').$alumno->avatar.'.jpg';
                if (!file_exists($realPath)) {
                    $avatar = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').strtolower($alumno->genre).'.jpg';
                }
                
                $convenio = $alumno->convenio == "0" ?
                                '<i data-toggle="tooltip" title="Convenio Pendiente" class="fa fa-file"></i>' : '';
                            
                $check = '<div class="custom-control custom-checkbox">
                            <input type="checkbox" 
                                   class="custom-control-input check-item" 
                                   id="customCheck'.$counter.'"
                                   data-inscribir="'.($finished ? 1 : 0).'" 
                                   value="'.$alumno->student_id.'">
                            <label class="custom-control-label" for="customCheck'.$counter.'">'.$counter.'</label>
                        </div>';
                            
                if ($user_type !== 3) {
                $photo = '<img class="rounded-circle btnChangeAvatar" 
                               src="'.$avatar.'"
                               data-student="'.$alumno->student_id.'" 
                               alt="foto" 
                               widt="42" 
                               height="42">';
                } else {
                $photo = '<img class="rounded-circle" src="'.$avatar.'" alt="foto" widt="42" height="42">';
                }

                $optClass = $finished ? 'danger' : 'primary';

                $options = '<a href="javascript:void(0)"
                         data-target="#"
                         class="btn btn-outline-'.$optClass.' btn-sm dropdown-toggle"
                         data-toggle="dropdown">Más.. <span class="caret"></span>
                      </a>';
                $options .= '<ul class="dropdown-menu student">';
                if ($user_type !== 3) {
                $options .= '<li>
                            <a href="'.Config::get('URL').'alumnos/perfil/'.$alumno->student_id.'"
                               data-student="'.$alumno->student_id.'"
                               data-tutor="'.$id_tutor.'"
                               data-clase="'.$id_grupo.'"
                               data-curso="'.$nombre_curso.'">
                                <i class="ml-1 text-dark fa fa-chevron-right"></i>
                                Perfil
                            </a>
                        </li>';
                $options .=    '<li>
                            <a href="'.Config::get('URL').'alumnos/c/'.$alumno->student_id.'">
                                <i class="ml-1 text-primary fa fa-chevron-right"></i>
                                Convenio
                            </a></li>';
                $options .=    '<li>
                            <a href="javascript:void(0)"
                               class="btnChangeAvatar"
                               data-student="'.$alumno->student_id.'">
                                <i class="ml-1 text-info fa fa-chevron-right"></i>
                                Cambiar Foto
                            </a>
                        </li>';
                }
                $options .=   '<li>
                            <a href="'.Config::get('URL').'evaluaciones/st/'.$alumno->student_id.'">
                                <i class="ml-1 text-success fa fa-chevron-right"></i>
                                Calificaciones
                            </a>
                        </li>';
                if ($user_type !== 3) {
                $options .=   '<li>
                            <a href="'.Config::get('URL').'mapa/u/'.$alumno->student_id.'">
                                <i class="ml-1 text-dark fa fa-chevron-right"></i>
                                Croquis
                            </a>
                        </li>';
                $options .=   '<li>
                            <a  href="javascript:void(0)" 
                                class="btnUnsuscribeStudent" 
                                data-student="'.$alumno->student_id.'"
                                data-name="'.$alumno->name.' '.$alumno->surname.'">
                                <i class="ml-1 text-warning fa fa-chevron-right"></i>
                                Dar de Baja
                            </a>
                        </li>';
                $options .=   '<li>
                            <a  href="javascript:void(0)" 
                                class="btnDeleteStudent" 
                                data-student="'.$alumno->student_id.'"
                                data-name="'.$alumno->name.' '.$alumno->surname.'">
                                <i class="ml-1 text-danger fa fa-chevron-right"></i>
                                Eliminar
                            </a>
                        </li>';
                }
                $options .= '</ul>';

                $dias = '<small>'.$dias.'</small>';

                $info = array(
                    'count'      => $check,
                    'avatar'     => $photo,
                    'surname'    => $alumno->surname.' '.$alumno->lastname.'<br> <strong>'.$becado.'</strong>',
                    'name'       => $alumno->name,
                    'studies'    => $alumno->studies.' '.$alumno->lastgrade,
                    'age'        => $alumno->age,
                    'group_name' => $grupo,
                    'teacher'    => ucwords(strtolower($maestro)),
                    'horary'     => $dias . '<br>' . $horario,
                    'options'    => $options,
                    'finished'   => $finished
                );

                array_push($datos, $info);
                $counter++;
            }
        }

        return array('data' => $datos);   
    }

    public static function countStudentsByClass(){
        $teacher  = Session::get('user_id');
        $database = DatabaseFactory::getFactory()->getConnection();
        $courses  =  $database->prepare("SELECT course_id FROM courses WHERE status = 1;");
        $courses->execute();

        $counters = array();

        // Alumnos por cada curso que exista
        if($courses->rowCount() > 0){
            $cursos = $courses->fetchAll();
            foreach ($cursos as $curso) {
                $label = 'count_'.$curso->course_id;
                $count = GeneralModel::countByTeacherCourse($teacher, $curso->course_id);
                $counters[$label] = $count;
            }
        }

        return $counters;
    }



    /**
    |===============================================================================================
    | R E Q U I R E   I N V O I C E   L I S T  [REQUIEREN FACTURA]
    |=============================================================================================== 
    */
    
    //->Obtener Alumnos que requieren de factura tras pago de colegiatura
    public static function getInvoiceTable(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $getAlumnos = "SELECT s.student_id, CONCAT_WS(' ', s.name, s.surname) as name, s.cellphone, s.id_tutor
                       FROM students as s, students_details as sd
                       WHERE s.student_id   = sd.student_id
                         AND sd.facturacion = 1;";
        $setAlumnos = $database->prepare($getAlumnos);
        $setAlumnos->execute();

        $students = [];
        if ($setAlumnos->rowCount() > 0) {
            $alumnos = $setAlumnos->fetchAll();

            $count    = 1;
            foreach ($alumnos as $alumno) {
                $tutor  = 'N/A';
                $phone  = 'N/A';
                $phone2 = 'N/A'; 
                $phone3 = 'N/A';

                if ($alumno->id_tutor !== '0') {
                    $getTutor = "SELECT CONCAT_WS(' ', namet, surnamet, lastnamet) as name, cellphone, phone, phone_alt
                                 FROM tutors
                                 WHERE id_tutor = :tutor
                                 LIMIT 1;";
                    $setTutor = $database->prepare($getTutor);
                    $setTutor->execute(array(':tutor' => $alumno->id_tutor));

                    if ($setTutor->rowCount() > 0) {
                        $datos  = $setTutor->fetch();
                        $tutor  = $datos->name;
                        $phone  = $datos->phone != ""     ? $datos->phone     : ' N/A';
                        $phone2 = $datos->cellphone != "" ? $datos->cellphone : ' N/A';
                        $phone3 = $datos->phone_alt != "" ? $datos->phone_alt : ' N/A';
                    }
                }

                $student = array(
                                'name'      => $alumno->name,
                                'cellphone' => $alumno->cellphone,
                                'tutor'     => $tutor,
                                'phone'     => $phone,
                                'phone2'    => $phone2,
                                'phone3'    => $phone3

                );
                array_push($students, $student);
                $count++;
            }
        }
        return array('data' => $students);
    }

    //->Obtener Alumnos que requieren de factura tras pago de colegiatura
    public static function getStudentsInvoiceList(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $getAlumnos = "SELECT s.student_id, s.name, s.surname, s.cellphone, s.id_tutor
                       FROM students as s, students_details as sd
                       WHERE s.student_id   = sd.student_id
                         AND sd.facturacion = 1;";
        $setAlumnos = $database->prepare($getAlumnos);
        $setAlumnos->execute();

        if ($setAlumnos->rowCount() > 0) {
            $alumnos = $setAlumnos->fetchAll();

            $students = array();
            foreach ($alumnos as $alumno) {
                $tutor  = '- - - -';
                $phone1 = '- - - -';
                $phone2 = '- - - -';
                $phone3 = '- - - -';

                if ($alumno->id_tutor !== '0') {
                    $getTutor = "SELECT namet, surnamet, lastnamet, cellphone, phone, phone_alt
                                 FROM tutors
                                 WHERE id_tutor = :tutor
                                 LIMIT 1;";
                    $setTutor = $database->prepare($getTutor);
                    $setTutor->execute(array(':tutor' => $alumno->id_tutor));

                    if ($setTutor->rowCount() > 0) {
                        $datos  = $setTutor->fetch();
                        $tutor  = ucwords(strtolower($datos->namet)).' '.
                                  ucwords(strtolower($datos->surnamet)).' '.
                                  ucwords(strtolower($datos->lastnamet));
                        $phone1 = $datos->cellphone != "" ? $datos->cellphone : ' - - - -';
                        $phone2 = $datos->phone != "" ? $datos->phone : ' - - - -';
                        $phone3 = $datos->phone_alt != "" ? $datos->phone_alt : ' - - - -';
                    }
                }

                $students[$alumno->student_id] = new stdClass();
                $students[$alumno->student_id]->name     = ucwords(strtolower($alumno->name)).' '.
                                                           ucwords(strtolower($alumno->surname));
                $students[$alumno->student_id]->cellphone = $alumno->cellphone;
                $students[$alumno->student_id]->tutor    = $tutor;
                $students[$alumno->student_id]->phone1   = $phone1;
                $students[$alumno->student_id]->phone2   = $phone2;
                $students[$alumno->student_id]->phone3   = $phone3;
            }
            self::showInvoiceList($students);
        } else {
            echo '<h4 class="text-center text-naatik subheader">Ningún alumno requiere facturación.</h4>';
        }
    }

    //->Mostrar Alumnos que requieren de factura tras pago de colegiatura
    public static function showInvoiceList($students) {
        if (count($students) > 0) {
            echo '<div class="table-responsive">';
                echo '<table id="tbl_invoice"
                             class="table table-bordered table-hover">';
                    echo '<thead>';
                        echo '<tr class="info">';
                            echo '<th class="text-center"> N° </th>';
                            echo '<th class="text-center">Alumno</th>';
                            echo '<th class="text-center">Celular</th>';
                            echo '<th class="text-center">Tutor</th>';
                            echo '<th class="text-center">Teléfono</th>';
                            echo '<th class="text-center">Celular</th>';
                            echo '<th class="text-center">Tel. Alterno</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        $c = 1;
                        foreach ($students as $row) {
                            echo '<tr class="row_data">';
                            echo '<td class="text-center">'.$c.'</td>';
                                echo '<td class="text-center txt">'.$row->name.'</td>';
                                echo '<td class="text-center txt">'.$row->cellphone.'</td>';
                                echo '<td class="text-center txt">'.$row->tutor.'</td>';
                                echo '<td class="text-center txt">'.$row->phone1.'</td>';
                                echo '<td class="text-center txt">'.$row->phone2.'</td>';
                                echo '<td class="text-center txt">'.$row->phone3.'</td>';
                            echo '</tr>';
                            $c++;
                        }
                    echo '</tbody>';
                echo '</table>';
                echo "<br><br><br>";
            echo '</div>';
        }
    }



    /**
    |===============================================================================================
    | C H A N G E   G R O U P  [CAMBIAR/ASIGNAR GRUPO]
    |=============================================================================================== 
    */

    public static function ChangeStudentGroup($alumno, $clase, $reinscribir){
        $database = DatabaseFactory::getFactory()->getConnection();

        $clase = (int)$clase === 0 ?  null : (int)$clase;
        $reinscribir = (int)$reinscribir === 1 ? true : false;
        $message = "No se realizo el cambio de grupo, intente de nuevo o reporte el error!";
        $commit   = true;
        $database->beginTransaction();
        try{
           
            if (@$database->query("SELECT 1 FROM student_history LIMIT 0;")) {
                $commit = true;
            } else {
                $commit  = false;
                $message = "Error hace falta una tabla, reporte el error a sistemas";
            }

            if ($commit && $reinscribir && $clase !== null) {
                $_student = $database->prepare("SELECT s.birthday, sd.ocupation, sd.workplace, 
                                                       sd.studies, sd.lastgrade, sg.class_id, sg.date_begin
                                                FROM students as s, 
                                                     students_details as sd, 
                                                     students_groups as sg
                                                WHERE s.student_id = :student
                                                  AND s.student_id = sd.student_id
                                                  AND s.student_id = sg.student_id
                                                LIMIT 1;");
                $_student->execute(array(':student' => $alumno));

                if ($_student->rowCount() > 0) {
                    $_student = $_student->fetch();

                    $_class  =  $database->prepare("SELECT cu.course, g.group_name, h.year, 
                                                           h.date_end, c.teacher_id
                                                    FROM classes as c, 
                                                         courses as cu, 
                                                         groups as g, 
                                                         schedules as h
                                                    WHERE c.course_id  = cu.course_id
                                                      AND c.group_id   = g.group_id
                                                      AND c.schedul_id = h.schedul_id 
                                                      AND c.class_id   = :clase
                                                    LIMIT 1;");
                    $_class->execute(array(':clase' => $_student->class_id));

                    $_class = $_class->fetch();

                    $teacher_name = '';
                    if ($_class->teacher_id != null && $_class->teacher_id != 0) {
                        $teacher = $database->prepare("SELECT name, lastname FROM users 
                                                       WHERE user_id = :user LIMIT 1;");
                        $teacher->execute(array(':user' => $_class->teacher_id));
                        $teacher = $teacher->fetch();
                        $teacher_name = $teacher->name . ' ' . $teacher->lastname;
                    }

                    $history = $database->prepare("INSERT INTO student_history(student_id,
                                                                                student_age,
                                                                                ciclo,
                                                                                student_group,
                                                                                teacher_group,
                                                                                student_init_date,
                                                                                student_end_date,
                                                                                student_school,
                                                                                student_grade,
                                                                                student_becado,
                                                                                student_sponsor,
                                                                                student_sep,
                                                                                created_at
                                                                            ) VALUES(
                                                                                :alumno,
                                                                                :edad,
                                                                                :ciclo,
                                                                                :grupo,
                                                                                :maestro,
                                                                                :fecha_inicio,
                                                                                :fecha_termino,
                                                                                :escuela,
                                                                                :grado,
                                                                                :becado,
                                                                                :padrino,
                                                                                :sep,
                                                                                :created_at
                                                                            );");
                    $history->execute(array(
                        ':alumno'       => $alumno,
                        ':edad'         => H::getAge($_student->birthday),
                        ':ciclo'        => $_class->year,
                        ':grupo'        => $_class->course . ' ' . $_class->group_name,
                        ':maestro'      => $teacher_name,
                        ':fecha_inicio' => $_student->date_begin,
                        ':fecha_termino' => $_class->date_end,
                        ':escuela'       => $_student->workplace,
                        ':grado'         => $_student->studies .' '. $_student->lastgrade,
                        ':becado'        => 0,
                        ':padrino'       => '',
                        ':sep'           => 0,
                        ':created_at'    => H::getTime()
                    ));

                    if ($history->rowCount() < 1) {
                        $commint = false;
                    }
                }
                
            }


            if ($commit) {
                $change = $database->prepare("UPDATE students_groups SET class_id = :clase WHERE student_id = :alumno;");
                $commit = $change->execute(array(':clase' => $clase, ':alumno' => $alumno));
                $status = 1;
                if($clase === null){
                    // En espera
                    $status = 2;
                }

                $update  = $database->prepare("UPDATE students SET status = :status WHERE student_id = :student;");
                $updated = $update->execute(array(':status' => $status, ':student' => $alumno));

                if (!$updated) {
                    $commit = false;
                }
            } else {
                $commit = false;
            }        
        } catch (PDOException $e) {
            $message = $e->getMessage();
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 
                         'message' => '&#x2718; '. $message);
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Cambio de grupo realizado correctamente!!');
        }
    }

    public static function ChangeStudentsGroup($alumnos, $clase){
        $database = DatabaseFactory::getFactory()->getConnection();

        $clase === '0' ? $clase = null : $clase = (int)$clase;
        $message = "No se realizo el cambio de grupo, intente de nuevo o reporte el error!";

        $commit   = true;
        $database->beginTransaction();
        try{
            if (@$database->query("SELECT 1 FROM student_history LIMIT 0;")) {
                $commit = true;
            } else {
                $commit  = false;
                $message = "Error hace falta una tabla, reporte el error a sistemas";
            }

            foreach ($alumnos as $alumno) {
                if (!$commit) {
                    break;
                }
                $datos = explode(',', $alumno);
                $alumno = $datos[0];
                $reinscribir = (int)$datos[1] === 1 ? true : false;

                if ($reinscribir && $clase !== null) {
                    $_student = $database->prepare("SELECT s.birthday, sd.ocupation, sd.workplace, 
                                                           sd.studies, sd.lastgrade, sg.class_id, sg.date_begin
                                                    FROM students as s, 
                                                         students_details as sd, 
                                                         students_groups as sg
                                                    WHERE s.student_id = :student
                                                      AND s.student_id = sd.student_id
                                                      AND s.student_id = sg.student_id
                                                    LIMIT 1;");
                    $_student->execute(array(':student' => $alumno));

                    if ($_student->rowCount() > 0) {
                        $_student = $_student->fetch();

                        $_class  =  $database->prepare("SELECT cu.course, g.group_name, h.year, 
                                                               h.date_end, c.teacher_id
                                                        FROM classes as c, 
                                                             courses as cu, 
                                                             groups as g, 
                                                             schedules as h
                                                        WHERE c.course_id  = cu.course_id
                                                          AND c.group_id   = g.group_id
                                                          AND c.schedul_id = h.schedul_id 
                                                          AND c.class_id   = :clase
                                                        LIMIT 1;");
                        $_class->execute(array(':clase' => $_student->class_id));

                        $_class = $_class->fetch();

                        $teacher_name = '';
                        if ($_class->teacher_id != null && $_class->teacher_id != 0) {
                            $teacher = $database->prepare("SELECT name, lastname FROM users 
                                                           WHERE user_id = :user LIMIT 1;");
                            $teacher->execute(array(':user' => $_class->teacher_id));
                            $teacher = $teacher->fetch();
                            $teacher_name = $teacher->name . ' ' . $teacher->lastname;
                        }

                        $history = $database->prepare("INSERT INTO student_history(student_id,
                                                                                    student_age,
                                                                                    ciclo,
                                                                                    student_group,
                                                                                    teacher_group,
                                                                                    student_init_date,
                                                                                    student_end_date,
                                                                                    student_school,
                                                                                    student_grade,
                                                                                    student_becado,
                                                                                    student_sponsor,
                                                                                    student_sep,
                                                                                    created_at
                                                                                ) VALUES(
                                                                                    :alumno,
                                                                                    :edad,
                                                                                    :ciclo,
                                                                                    :grupo,
                                                                                    :maestro,
                                                                                    :fecha_inicio,
                                                                                    :fecha_termino,
                                                                                    :escuela,
                                                                                    :grado,
                                                                                    :becado,
                                                                                    :padrino,
                                                                                    :sep,
                                                                                    :created_at
                                                                                );");
                        $history->execute(array(
                            ':alumno'       => $alumno,
                            ':edad'         => H::getAge($_student->birthday),
                            ':ciclo'        => $_class->year,
                            ':grupo'        => $_class->course . ' ' . $_class->group_name,
                            ':maestro'      => $teacher_name,
                            ':fecha_inicio' => $_student->date_begin,
                            ':fecha_termino' => $_class->date_end,
                            ':escuela'       => $_student->workplace,
                            ':grado'         => $_student->studies .' '. $_student->lastgrade,
                            ':becado'        => 0,
                            ':padrino'       => '',
                            ':sep'           => 0,
                            ':created_at'    => H::getTime()
                        ));

                        if ($history->rowCount() < 1) {
                            $commint = false;
                        }
                    }
                }


                if ($commit) {
                    $change = $database->prepare("UPDATE students_groups SET class_id = :clase WHERE student_id = :alumno;");
                    $save = $change->execute(array(':clase' => $clase, ':alumno' => $alumno));
                    $status = 1;
                    if($clase === null){
                        $status = 2;
                    }
                    $update = $database->prepare("UPDATE students SET status = :status WHERE student_id = :student;");
                    $updated = $update->execute(array(':status' => $status, ':student' => $alumno));
                } else {
                    $commit = false;
                    break;
                }
            }                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; ' . $message);
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Cambio de grupo realizado correctamente!!');
        }
    }



    /**
    |===============================================================================================
    | C R E A T E   N E W   S T U D E N T  [INSCRIBIR]
    |=============================================================================================== 
    */

    public static function tutorExist($name, $surname, $lastname){
        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = '%'.trim($name).'%';
        $surname  = '%'.trim($surname).'%';
        $lastname = '%'.trim($lastname).'%';

        $sql = "SELECT id_tutor, namet, surnamet, lastnamet, job
                FROM tutors
                WHERE namet     LIKE :name
                  AND surnamet  LIKE :surname
                  AND lastnamet LIKE :lastname;";
        $query = $database->prepare($sql);
        $query->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));

        if ($query->rowCount() > 0) {
            $tutor = $query->fetch();

            $address = $database->prepare("SELECT id_address
                                            FROM address 
                                            WHERE user_id = :tutor 
                                              AND user_type = 1
                                            LIMIT 1;");
            $address->execute(array(':tutor' => $tutor->id_tutor));

            if ($address->rowCount() > 0) {
                $address = $address->fetch();
                $tutor->address = $address->id_address;
            }
            return $tutor;
        }
        return null;
    }

    public static function getTutorByID($tutor) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql =  $database->prepare("SELECT t.*, a.* 
                                    FROM tutors as t, address as a
                                    WHERE t.id_tutor = :tutor
                                      AND t.id_tutor = user_id
                                      AND user_type  = 1
                                    LIMIT 1;");
        $sql->execute(array(':tutor' => $tutor));

        if ($sql->rowCount() > 0) {
            return $sql->fetch();
        }

        return null;
    }

    public static function studentExist($name, $surname, $lastname){
        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = '%'.trim($name).'%';
        $surname  = '%'.trim($surname).'%';
        $lastname = '%'.trim($lastname).'%';

        $sql = "SELECT s.student_id, CONCAT_WS(' ', s.name, s.surname, s.lastname) as name, s.status, s.deleted,
                        g.class_id
                FROM students as s, students_groups as g
                WHERE name     LIKE :name
                  AND surname  LIKE :surname
                  AND lastname LIKE :lastname
                  AND s.student_id = g.student_id;";
        $query = $database->prepare($sql);
        $query->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));

        if ($query->rowCount() > 0) {
            $alumno = $query->fetch();

            $grupo = 'Espera';
            if ($alumno->class_id != null) {
                $clase = $database->prepare("SELECT CONCAT_WS(' ', cu.course, g.group_name) as grupo
                                             FROM classes as c, courses as cu, groups as g
                                             WHERE c.class_id  = :clase
                                               AND c.course_id = cu.course_id
                                               AND c.group_id  = g.group_id;");
                $clase->execute(array(':clase' => $alumno->class_id));
                $clase = $clase->fetch();
                $grupo = $clase->grupo;
            }
            $estado = 'Activo';
            switch ((int)$alumno->status) {
                case 0: $estado = 'De Baja'; break;
                case 1: $estado = 'Activo'; break;
                case 2: $estado = 'En Espera'; break;
                case 3: $estado = 'Egresado'; break;
            }
            
            $alumno->grupo  = $grupo;
            $alumno->estado = $estado;
            $alumno->eliminado = (int)$alumno->deleted;

            return $alumno;
        }
        return null;
    }

    public static function createTutor($name, $surname, $lastname, $relationship, $ocupation, $cellphone, $phone, $phoneAlt, $relationshipAlt, $street, $number, $between, $colony){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $tutor    = null;
        $address  = null;

        $name     = ucwords(strtolower($name));
        $surname  = ucwords(strtolower($surname));
        $lastname = ucwords(strtolower($lastname));

        $validar = $database->prepare("SELECT id_tutor 
                                        FROM tutors 
                                        WHERE namet = :name
                                          AND surnamet = :surname
                                          AND lastnamet = :lastname
                                        LIMIT 1;");
        $validar->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));
        if ($validar->rowCount() > 0) {
            return array('success' => false, 'message' => "Esta tratando de guardar un tutor que ya existe.");
        }

        $database->beginTransaction();
        try{
            $query= $database->prepare("INSERT INTO tutors(namet, surnamet, lastnamet, 
											               job, cellphone, phone, relationship, 
											               phone_alt, relationship_alt)
			                                        VALUES(:name, :surname, :lastname, 
					                                       :job, :cellphone, :phone, :relation, 
					                                       :phone_alt, :relation_alt);");
            $query->execute(array(
                                ':name'         => $name,
                                ':surname'      => $surname,
                                ':lastname'     => $lastname,
                                ':job'          => $ocupation,
                                ':cellphone'    => $cellphone,
                                ':phone'        => $phone,
                                ':relation'     => $relationship,
                                ':phone_alt'    => $phoneAlt,
                                ':relation_alt' => $relationshipAlt
                            ));

            if ($query->rowCount() > 0) {
            	$tutor = $database->lastInsertId();
                $_sql = $database->prepare("INSERT INTO address(user_id, user_type, street, st_number, 
										                	    st_between, colony,city, zipcode, state, 
										                	    country)
				                                		VALUES(:user, :user_type, :street, :st_number, 
						                                	   :st_between, :colony, :city, :zipcode, :state, 
						                                	   :country);");
	            $_sql->execute(array(
					                ':user'       => $tutor,
					                ':user_type'  => 1,
					                ':street'     => $street,
					                ':st_number'  => $number,
					                ':st_between' => $between,
					                ':colony'     => $colony,
					                ':city'       => 'Felipe Carrillo Puerto',
					                ':zipcode'    => 77200,
					                ':state'      => 'Quintana Roo',
					                ':country'    => 'México'));

	            if ($_sql->rowCount() > 0) {
	                $address = $database->lastInsertId();
	            } else {
	            	$commit = false;
	            }
            } else {
            	$commit = false;
            }           
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => "Error: No se guardó datos del tutor, intente de nuevo o reporte el error.");
        } else {
            $database->commit();
            return array(
	            		'success' => true, 
	            		'tutor'   => $tutor, 
	            		'name'    => $name.' '.$surname.' '.$lastname,
	            		'address' => $address,
	            		'street'  => $street,
	            		'number'  => $number,
	            		'between' => $between,
	            		'colony'  => $colony,
	            		'message' => 'Se guardo correctamente datos del tutor');
        }
    }

    public static function createStudent($tutor, $address, $surname, $lastname, $name, $birthday, $age, $genre, $civil_status, $cellphone, $reference, $sickness, $medication, $comment, $invoice, $homestay, $acta, $street, $number, $between, $colony) {
    	$database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $student  = null;

        $name     = ucwords(strtolower($name));
        $surname  = ucwords(strtolower($surname));
        $lastname = ucwords(strtolower($lastname));
        $tutor    = (int)$tutor;

        $validar = $database->prepare("SELECT student_id
                                        FROM students
                                        WHERE name = :name
                                          AND surname = :surname
                                          AND lastname = :lastname
                                        LIMIT 1;");
        $validar->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));
        if ($validar->rowCount() > 0) {
            return array('success' => false, 'message' => "Esta tratando de guardar un alumno que ya existe.");
        }

        $database->beginTransaction();
        try{

        	$sql = $database->prepare("INSERT INTO students(id_tutor, name, surname, lastname,
	                                                        birthday, age, genre, edo_civil,
	                                                        cellphone, reference, sickness,
	                                                        medication, comment_s)
	                                                VALUES(:tutor, :name, :surname, :lastname,
	                                                        :birthday, :age, :genre, :edo_civil,
	                                                        :cellphone, :reference, :sickness,
	                                                        :medication, :comment_s);");
	            $sql->execute(array(':tutor'      => $tutor,
	                                ':name'       => $name,
	                                ':surname'    => $surname,
	                                ':lastname'   => $lastname,
	                                ':birthday'   => $birthday,
	                                ':age'        => $age,
	                                ':genre'      => $genre,
	                                ':edo_civil'  => $civil_status,
	                                ':cellphone'  => $cellphone,
	                                ':reference'  => $reference,
	                                ':sickness'   => $sickness,
	                                ':medication' => $medication,
	                                ':comment_s'  => $comment));

	        if ($sql->rowCount() > 0) {
                $select = $database->prepare("SELECT student_id FROM students ORDER BY student_id DESC LIMIT 1;");
                $select->execute();
                $student = $database->lastInsertId();
                if ($select->rowCount() > 0) {
                    $student = $select->fetch()->student_id;
                }
	            
	            // Crear detalles del alumno
	            $details =  $database->prepare("INSERT INTO students_details(student_id, facturacion, homestay,
                                                                             acta_nacimiento) 
                                                                    VALUES(:student, :invoice, :homestay, :acta)");
                $details->execute(array(':student'  => $student,
                                        ':invoice'  => $invoice,
                                        ':homestay' => $homestay,
                                        ':acta'     => $acta));
                if ($details->rowCount() > 0) {
                	// Si no tiene tutor agregamos una entrada en la tabla address
                	if ($tutor === 0) {
                		$_sql = $database->prepare("INSERT INTO address(user_id, user_type, street, st_number, 
												                	    st_between, colony,city, zipcode, state, 
												                	    country)
						                                		VALUES(:user, :user_type, :street, :st_number, 
								                                	   :st_between, :colony, :city, :zipcode, :state, 
								                                	   :country);");
			            $_sql->execute(array(
							                ':user'       => $student,
							                ':user_type'  => 2,
							                ':street'     => $street,
							                ':st_number'  => $number,
							                ':st_between' => $between,
							                ':colony'     => $colony,
							                ':city'       => 'Felipe Carrillo Puerto',
							                ':zipcode'    => 77200,
							                ':state'      => 'Quintana Roo',
							                ':country'    => 'México'));
			            if ($_sql->rowCount() < 1) {
			            	$commit = false;
			            }
                	}
                } else {
                	$commit = false;
                }

            } else {
            	$commit = false;
            }           
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();

            return array(
            			'success' => false, 
            			'message' => "Error: No se guardó datos del alumno, intente de nuevo o reporte el error.");
        } else {
            $database->commit();
            return array(
	            		'success' => true, 
	            		'student' => $student, 
	            		'name'    => $name.' '.$surname.' '.$lastname,
	            		'genre'   => strtoupper($genre),
	            		'message' => 'Se guardo correctamente datos del Alumno');
        }
    }

    public static function createAvatar($student, $avatar_name){
    	$database = DatabaseFactory::getFactory()->getConnection();

        $sql = $database->prepare('SELECT avatar FROM students WHERE student_id = :student LIMIT 1;');
        $sql->execute(array(':student' => $student));

        // Remove the old avatar
        if ($sql) {
            $result  = $sql->fetch();
            $realPath = Config::get('PATH_AVATARS_STUDENTS').$result->avatar.'.jpg';
            $image    = strtolower($result->avatar);
            if (!file_exists($realPath && $image !== 'masculino' && $image !== 'femenino')) {
                unlink($realPath);
            }
        }


    	$avatar = $database->prepare('UPDATE students SET avatar = :avatar WHERE student_id = :student;');
    	$save = $avatar->execute(array(':avatar' => $avatar_name, ':student' => $student));

    	if ($save) {
    		return array('success' => true, 'message' => 'Se guardo correctamente datos del Alumno');
    	}
    	return array('success' => false, 'message' => 'No se guardo la foto del alumno, intente mas tarde.');
    }

    public static function createStudies($student, $ocupation, $workplace, $studies, $lastgrade, $prior_course, $prior_comments, $class_id, $inscription_date, $date_start){
    	$database = DatabaseFactory::getFactory()->getConnection();

    	$commit   = true;
        $ciclo    = H::getCiclo(date('m'));
        $database->beginTransaction();
        try{
        	//Agregar detalles del alumno
	        $details =  $database->prepare("UPDATE students_details 
								        	SET ocupation      = :ocupation, 
								        		workplace      = :workplace, 
								        		studies        = :studies, 
								        		lastgrade      = :lastgrade, 
								        		prior_course   = :prior_course, 
								        		prior_comments = :prior_comments
								        	WHERE student_id = :student;");

	        $update  =  $details->execute(array(':student'        => $student,
				                                ':ocupation'      => $ocupation,
				                                ':workplace'      => $workplace,
				                                ':studies'        => $studies,
				                                ':lastgrade'      => $lastgrade,
				                                ':prior_course'   => $prior_course,
				                                ':prior_comments' => $prior_comments));
 

            if ($update) {
            	$group = $database->prepare("INSERT INTO students_groups(class_id, student_id, date_begin, 
													            		 year, ciclo, prior_course) 
			                                                        VALUES(:clase, :student, :begin_date, 
					                                                       :year, :ciclo, :prior_course)");
			    $group->execute(array(':clase'        => $class_id,
			                          ':student'      => $student,
			                          ':begin_date'   => $date_start,
				                      ':year'         => date('Y'),
					                  ':ciclo'        => $ciclo,
						              ':prior_course' => $prior_comments));

                // Update Date inscription into students table
                $updateDate = $database->prepare("UPDATE students SET created_at = :inscription_date WHERE student_id = :student;");
                $updateDate->execute(array(':inscription_date' => $inscription_date, ':student' => $student));

			    if ($group->rowCount() < 1) {
			    	$commit = false;
			    }
	         
            } else {
            	$commit = false;
            }           
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();

            return array(
	            		'success' => false, 
	            		'message' => "Error: No se guardó la información, intente de nuevo o reporte el error.");
        } else {
            $database->commit();
            return array(
	            		'success' => true,
	            		'student_id' => $student,
	            		'message' => 'Registro finalizado');
        }
    }



    /**
    |===============================================================================================
    | U N S U S C R I B E   S T U D E N T  [DAR DE BAJA]
    |=============================================================================================== 
    */
   
    // TABLA DE ALUMNOS DE BAJA
    public static function unsuscribeStudentsTable(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = GeneralModel::allStudentsUnsuscribe();

        if ($students !== null) {

            $datos = [];
            $counter = 1;
            foreach ($students as $alumno) {
                $id_grupo = 0;
                $grupo = 'Sin Grupo';

                if ($alumno->class_id !== NULL) {
                    $clase = $database->prepare("SELECT c.class_id, c.course_id, CONCAT_WS(' ', cu.course, g.group_name) as grupo
                                                 FROM classes as c, courses as cu, groups as g
                                                 WHERE c.class_id  = :clase
                                                   AND c.status    = 1
                                                   AND c.course_id = cu.course_id
                                                   AND c.group_id  = g.group_id
                                                 LIMIT 1;");
                    $clase->execute(array(':clase' => $alumno->class_id));
                    if ($clase->rowCount() > 0) {
                        $clase = $clase->fetch();
                        $id_grupo = $clase->class_id;
                        $grupo = $clase->grupo;
                    }
                }

                //-> Tutor del Alumno
                $id_tutor     = 0;
                $nombre_tutor = 'N/A';
                if ($alumno->id_tutor !== NULL) {
                    $tutor = $database->prepare("SELECT id_tutor, CONCAT_WS(' ', namet, surnamet, lastnamet) as name
                                                    FROM tutors
                                                    WHERE id_tutor = :tutor
                                                 LIMIT 1;");
                    $tutor->execute(array(':tutor' => $alumno->id_tutor));
                    if ($tutor->rowCount() > 0) {
                        $tutor = $tutor->fetch();
                        $id_tutor = $tutor->id_tutor;
                        $nombre_tutor = $tutor->name;
                    }
                }

                $url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').$alumno->avatar;

                if (!file_exists($url)) {
                    $url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').strtolower($alumno->genre).'.jpg';
                }
                $avatar = '<img class="rounded-circle" src="'.$url.'" alt="foto" widt="42" height="42">';
                $editar = '<button type="button" 
                                    class="btn btn-sm btn-info btnSuscribeStudent mr-2 text-white"
                                    data-student="'.$alumno->student_id.'"
                                    data-name="'.$alumno->name.'"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Alta"><i class="fa fa-user-plus"></i></button>';
                $eliminar = '<button type="button" 
                                    class="btn btn-sm btn-danger btnDeleteStudent"
                                    data-student="'.$alumno->student_id.'"
                                    data-name="'.$alumno->name.'"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Eliminar"><i class="fa fa-user-times"></i></button>';

                // $fecha_baja = new DateTime($alumno->fecha_baja);
                // $fecha_hoy  = new DateTime(date('Y-m-d'));
                // $time       = $fecha_baja->diff($fecha_hoy);
                // $anios      = $time->y > 0 ? $time->y . ' Años, ' : '';
                // $meses      = $time->m > 0 ? $time->m . ' Meses' : '';
                $fecha_baja = $alumno->fecha_baja !== null ? H::formatShortDate($alumno->fecha_baja) : 'No especificado';

                $info = array(
                    'count'     => $counter,
                    'name'      => $alumno->name,
                    'age'       => $alumno->age,
                    'avatar'    => $avatar,
                    'studies'   => $alumno->studies.' '.$alumno->lastgrade,
                    'group'     => $grupo,
                    'tutor'     => $nombre_tutor,
                    'fecha'     => $fecha_baja,
                    'motivo'    => $alumno->motivo_baja === null ? 'No especificado' : $alumno->motivo_baja,
                    'options'   => $editar . $eliminar
                );

                array_push($datos, $info);
                $counter++;
            }

            return array('data' => $datos);
        }

        return null;    
    }

    // DAR DE BAJA ALUMNO
    public static function unsuscribeStudent($student, $unsuscribe_date, $unsuscribe_note){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $database->beginTransaction();
        try{
            $checkout = $database->prepare("UPDATE students
                                            SET status       = 0,
                                                fecha_baja   = :unsuscribe_date,
                                                motivo_baja  = :unsuscribe_note
                                            WHERE student_id = :student;");
            $update = $checkout->execute([
                                    ':student' => $student,
                                    ':unsuscribe_date' => $unsuscribe_date,
                                    ':unsuscribe_note' => $unsuscribe_note
                                ]);

            if (!$update) {
                $commit = false;
            }
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; No se dio de baja al alumno, intente de nuevo o reporte el error!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Alumno dado de baja correctamente!!');
        }
    }

    // DAR DE BAJA ALUMNOS
    public static function unsuscribeStudents($students){
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = (array)$students;
        $commit   = true;
        $database->beginTransaction();
        try{
            foreach ($students as $student) {
                $checkout = $database->prepare("UPDATE students
                                                SET status       = 0
                                                WHERE student_id = :student;");
                $update = $checkout->execute(array(':student' => $student));

                if (!$update) {
                    $commit = false;
                    break;
                }
            }
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; No se dio de baja al alumno, intente de nuevo o reporte el error!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Alumno dado de baja correctamente!!');
        }
    }

    // DAR DE ALTA ALUMNO
    public static function suscribeStudent($student){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $database->beginTransaction();
        try{
            $checkout = $database->prepare("UPDATE students
                                            SET status       = 1
                                            WHERE student_id = :student;");
            $update = $checkout->execute(array(':student' => $student));

            if (!$update) {
                $commit = false;
            }
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; No se dio de alta al alumno, intente de nuevo o reporte el error!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Alumno dado de alta correctamente!!');
        }
    }



    /**
    |===============================================================================================
    | S T U D E N T    P R O F I L E  [PERFIL]
    |=============================================================================================== 
    */
   
    
    public static function studentProfile($student){
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT s.student_id, s.id_tutor, s.name, 
                                            s.age, s.genre, s.cellphone, s.avatar, s.status,
                                            s.created_at, g.class_id 
                                     FROM students as s, students_groups as g
                                     WHERE s.student_id = :student
                                       AND s.student_id = g.student_id 
                                     LIMIT 1;");
        $query->execute(array(':student' => $student));

        if ($query->rowCount() > 0) {
            $alumno = $query->fetch();

            $avatar   = Config::get('URL').Config::get('PATH_AVATAR_STUDENT'). $alumno->avatar . '.jpg';
            $realPath = Config::get('PATH_AVATARS_STUDENTS').$alumno->avatar.'.jpg';
            if (!file_exists($realPath)) {
                $avatar = Config::get('URL').Config::get('PATH_AVATAR_STUDENT'). $alumno->genre . '.jpg';
            }

            $alumno->avatar = $avatar;
            $fecha_alta = new DateTime($alumno->created_at);
            $fecha_hoy  = new DateTime(date('Y-m-d'));
            $time       = $fecha_alta->diff($fecha_hoy);
            $anios      = $time->y > 0 ? $time->y . ' Años, ' : '';
            $meses      = $time->m > 0 ? $time->m . ' Meses' : '';


            $alumno->tiempo = $anios . $meses;
            $alumno->created_at = H::formatDate(date('Y-m-d', strtotime($alumno->created_at)));

            $grupo = null;
            if ($alumno->class_id !== NULL) {
                $clase  =  "SELECT cu.course, g.group_name
                            FROM classes as c, courses as cu, groups as g
                            WHERE c.class_id  = :clase
                              AND c.course_id = cu.course_id
                              AND c.group_id  = g.group_id
                              AND c.status    = 1
                            LIMIT 1;";
                $clase = $database->prepare($clase);
                $clase->execute(array(':clase' => $alumno->class_id));
                if ($clase->rowCount() > 0) {
                    $clase = $clase->fetch();
                    $grupo = ucwords(strtolower($clase->course)) . ' ' . 
                             ucwords(strtolower($clase->group_name));
                }
            }
            $alumno->grupo = $grupo;

            $tutor = null;
            if ($alumno->id_tutor !== 0) {
                $sql =  $database->prepare("SELECT CONCAT_WS(' ',namet, surnamet, lastnamet) as name,
                                                   cellphone, phone, relationship,
                                                   phone_alt
                                            FROM tutors
                                            WHERE id_tutor = :tutor
                                            LIMIT 1;");
                $sql->execute(array(':tutor' => $alumno->id_tutor));
                if ($sql->rowCount() > 0) {
                    $tutor = $sql->fetch();
                }
            }
            $alumno->tutor = $tutor;

            return $alumno;
        }
    }

    public static function studentProfileData($student){
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT *
                                     FROM students as s, students_details as sd
                                     WHERE s.student_id = :student
                                       AND s.student_id = sd.student_id
                                     LIMIT 1;");
        $query->execute(array(':student' => $student));

        if ($query->rowCount() > 0) {
            $alumno = $query->fetch();
            $address_owner = $alumno->student_id;
            $owner_type    = 2;        

            $address = null;
            if ((int)$alumno->id_tutor !== 0) {
                $address_owner = $alumno->id_tutor;
                $owner_type    = 1;
            }

            $_sql = $database->prepare("SELECT id_address, street, st_number, st_between, colony,
                                                city, zipcode, state, country 
                                        FROM address 
                                        WHERE user_id = :user AND user_type = :type;");
            $_sql->execute(array(':user' => $address_owner, ':type' => $owner_type));
            
            if ($_sql->rowCount() > 0) {
                $address = $_sql->fetch();
            }

            $alumno->address = $address;

            return $alumno;
        }

        return null;
    }

    public static function tutorProfileData($tutor){
        $database = DatabaseFactory::getFactory()->getConnection();
        if ((int)$tutor !== 0) {
            $_sql = $database->prepare("SELECT id_tutor, namet, surnamet, lastnamet,
                                               job, cellphone, phone, relationship,
                                               phone_alt, relationship_alt
                                        FROM tutors
                                        WHERE id_tutor = :tutor
                                        LIMIT 1;");

            $_sql->execute(array(':tutor' => $tutor));

            if ($_sql->rowCount() > 0) {
                return $_sql->fetch();
            }
        }

        return null;
    }

    public static function studiesProfileData($student){
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT s.created_at, sd.*, sg.class_id
                                     FROM students as s, students_details as sd, students_groups as sg
                                     WHERE sd.student_id = :student
                                       AND sg.student_id = :student
                                       AND s.student_id  = :student
                                     LIMIT 1;");
        $query->execute(array(':student' => $student));
        

        if ($query->rowCount() > 0) {
            $details = $query->fetch();
            $clase = null;
            if ($details->class_id != null) {
                $_sql = $database->prepare("SELECT c.class_id, c.course_id,
                                                   c.group_id, cu.course, g.group_name
                                             FROM classes as c, courses as cu, groups as g
                                             WHERE c.class_id  = :clase
                                               AND c.course_id = cu.course_id
                                               AND c.group_id  = g.group_id
                                             LIMIT 1;");
                $_sql->execute(array(':clase' => $details->class_id));

                if ($_sql->rowCount() > 0) {
                    $clase = $_sql->fetch();
                }
            }
            $details->clase = $clase;

            $beca = null;
            $getBeca = $database->prepare('SELECT * FROM becas 
                                           WHERE student_id = :student
                                             AND status IN (1,2)
                                           LIMIT 1;');
            $getBeca->execute(array(':student' => $details->student_id));
            if ($getBeca->rowCount() > 0) {
                $beca = $getBeca->fetch();
            }
            $details->beca = $beca;

            return $details;
        }

        return null;
    }


    /**
    |===============================================================================================
    | U P D A T E   S T U D E N T    I N F O R M A T I O N  [ACTUALIZAR]
    |=============================================================================================== 
    */
   
    public static function updateTutor($tutor, $name, $surname, $lastname, $ocupation, $relation, $phone, $cellphone, $relation_alt, $phone_alt){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;

        $name     = strtolower(trim($name));
        $surname  = strtolower(trim($surname));
        $lastname = strtolower(trim($lastname));

        $database->beginTransaction();
        try{
            $_sql = $database->prepare("UPDATE tutors
                                        SET namet            = :name,
                                            surnamet         = :surname,
                                            lastnamet        = :lastname,
                                            job              = :job,
                                            phone            = :phone,
                                            cellphone        = :cellphone,
                                            relationship     = :relation,
                                            phone_alt        = :phone_alt,
                                            relationship_alt = :relation_alt
                                        WHERE id_tutor = :tutor");
            $update = $_sql->execute(array(':name'          => ucwords($name),
                                            ':surname'      => ucwords($surname),
                                            ':lastname'     => ucwords($lastname),
                                            ':job'          => trim($ocupation),
                                            ':phone'        => trim($phone),
                                            ':cellphone'    => trim($cellphone),
                                            ':relation'     => $relation,
                                            ':phone_alt'    => trim($phone_alt),
                                            ':relation_alt' => $relation_alt,
                                            ':tutor'        => $tutor
                                        ));
        }catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => 'Error al actualizar, intente de nuevo o reporte el problema!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => 'Datos del tutor actualizados correctamente!');
        }
    }

    public static function updateStudent($student, $name, $surname, $lastname, $birthday, $genre, $edo_civil, $cellphone, $reference, $sickness, $medication, $homestay, $acta, $invoice, $comment, $address, $street, $number, $between, $colony, $city, $zipcode, $state, $country) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = ucwords(strtolower(trim($name)));
        $surname  = ucwords(strtolower(trim($surname)));
        $lastname = ucwords(strtolower(trim($lastname)));
        $age      = H::getAge($birthday);

        $commit   = true;
        $database->beginTransaction();
        try{
            $_sql = $database->prepare("UPDATE students 
                                        SET name       = :name,
                                            surname    = :surname,
                                            lastname   = :lastname,
                                            birthday   = :birthdate,
                                            age        = :age,
                                            genre      = :genre,
                                            edo_civil  = :edo_civil,
                                            cellphone  = :cellphone,
                                            reference  = :reference,
                                            sickness   = :sickness,
                                            medication = :medication,
                                            comment_s  = :comentario
                                        WHERE student_id = :student;");
            $update  =  $_sql->execute(array(':name'      => $name,
                                            ':surname'   => $surname,
                                            ':lastname'  => $lastname,
                                            ':birthdate' => $birthday,
                                            ':age'       => $age,
                                            ':genre'     => $genre,
                                            ':edo_civil' => $edo_civil,
                                            ':cellphone' => $cellphone,
                                            ':reference' => $reference,
                                            ':sickness'  => $sickness,
                                            ':medication' => $medication,
                                            ':comentario' => $comment,
                                            ':student' => $student));

            if ($update) {
                $_sql = null;

                $set_details =  $database->prepare("UPDATE students_details
                                                    SET facturacion     = :factura,
                                                        homestay        = :homestay,
                                                        acta_nacimiento = :acta
                                                    WHERE student_id = :student;");
                $set_details->execute(array(':factura'  => $invoice,
                                            ':homestay' => $homestay,
                                            ':acta'     => $acta,
                                            ':student'  => $student));
                
                $_sql = $database->prepare("UPDATE address 
                                            SET street     = :street, 
                                                st_number  = :st_number, 
                                                st_between = :st_between, 
                                                colony     = :colony,
                                                city       = :city, 
                                                zipcode    = :zipcode, 
                                                state      = :state, 
                                                country    = :country
                                            WHERE id_address = :address;");
                $save = $_sql->execute(array(
                        ':address'    => $address,
                        ':street'     => ucwords(strtolower(trim($street))),
                        ':st_number'  => trim($number),
                        ':st_between' => trim($between),
                        ':colony'     => ucwords(strtolower(trim($colony))),
                        ':city'       => ucwords(strtolower(trim($city))),
                        ':zipcode'    => trim($zipcode),
                        ':state'      => ucwords(strtolower(trim($state))),
                        ':country'    => ucwords(strtolower(trim($country)))
                ));

                if (!$save) {
                    $commit = false;
                }
            } else {
                $commit = false;
            }             
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => 'Error al actualizar, intente de nuevo o reporte el problema!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => 'Datos del Alumno actualizados correctamente!');
        }
    }

    public static function updateStudies($student, $ocupation, $workplace, $studies, $lastgrade, $inscription_date, $class){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $database->beginTransaction();
        try {
            $sql =  $database->prepare("UPDATE students_details
                                        SET ocupation  = :ocupacion,
                                            workplace  = :lugar,
                                            studies    = :estudios,
                                            lastgrade  = :grado
                                        WHERE student_id = :alumno;");
            $update  =  $sql->execute(array(':ocupacion' => $ocupation,
                                            ':lugar'     => $workplace,
                                            ':estudios'  => $studies,
                                            ':grado'     => $lastgrade,
                                            ':alumno'    => $student));

            if ($update) {
                $_sql = $database->prepare("UPDATE students_groups 
                                            SET class_id = :clase
                                            WHERE student_id = :student;");

                $_update = $database->prepare("UPDATE students SET created_at = :inscription_date WHERE student_id = :student;");
                $_update->execute(array(':inscription_date' => $inscription_date, ':student' => $student));

                $commit == $_sql->execute(array(':clase' => $class, ':student' => $student));
                
            } else {
                $commit = false;
            }
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; No se realizo la actualización, intente de nuevo o reporte error!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Datos academicos actualizados correctamente!!');
        }
    }




    /**
    |===============================================================================================
    | A B S E N C E S   S T U D E N T S  [FALTAS]
    |=============================================================================================== 
    */
   
    public static function getAbsencesList($year=null, $month=null){
        $database = DatabaseFactory::getFactory()->getConnection();

        $year = $year === null ? H::getTime('Y') : $year;
        $month = $month === null ? H::getTime('m') : $month;

        if((int)Session::get('user_type') !== 3){
            $getList =  $database->prepare("SELECT CONCAT_WS(' ', s.name, s.surname, s.lastname) as student,
                                                   ab.course,
                                                   REPLACE(ab.horary,'&','<br>') as horario,
                                                   CONCAT_WS(' ', t.name, t.lastname) as teacher,
                                                   ab.absence_note,
                                                   ab.teacher_note,
                                                   ab.absence_date,
                                                   ab.contact_date,
                                                   ab.return_date,
                                                   ab.teacher_id,
                                                   ab.absence_id
                                            FROM students_absences as ab, students as s, users as t
                                            WHERE YEAR(ab.absence_date) = :year
                                              AND MONTH(ab.absence_date) = :month
                                              AND ab.student_id = s.student_id
                                              AND ab.teacher_id = t.user_id
                                              AND ab.status     = 1
                                            ORDER BY ab.absence_date DESC;");
            $getList->execute([':year' => $year, ':month' => $month]);
        } else {
            $getList =  $database->prepare("SELECT CONCAT_WS(' ', s.name, s.surname, s.lastname) as student,
                                                   ab.course,
                                                   REPLACE(ab.horary,'&','<br>') as horario,
                                                   CONCAT_WS(' ', t.name, t.lastname) as teacher,
                                                   ab.absence_note,
                                                   ab.teacher_note,
                                                   ab.absence_date,
                                                   ab.contact_date,
                                                   ab.return_date,
                                                   ab.teacher_id,
                                                   ab.absence_id
                                            FROM students_absences as ab, students as s, users as t
                                            WHERE YEAR(ab.absence_date) = :year
                                              AND MONTH(ab.absence_date) = :month
                                              AND ab.student_id = s.student_id
                                              AND ab.teacher_id = :teacher
                                              AND ab.teacher_id = t.user_id
                                              AND ab.status     = 1
                                            ORDER BY ab.absence_date DESC;");
            $getList->execute([':year' => $year, ':month' => $month, ':teacher' => Session::get('user_id')]);
        }

        return $getList->fetchAll();
    }
   
    public static function saveAbsence($student, $absence_date, $teacher, $comment, $absence_note, $contact_date, $return_date){
        $database = DatabaseFactory::getFactory()->getConnection();

        $getClase = $database->prepare("SELECT cu.course, g.group_name, c.teacher_id, c.schedul_id, h.hour_init, h.hour_end
                                        FROM classes as c, courses as cu, groups as g, students_groups as sg, schedules as h
                                        WHERE sg.student_id = :student
                                          AND sg.class_id   = c.class_id
                                          AND c.course_id   = cu.course_id
                                          AND c.group_id    = g.group_id
                                          AND c.schedul_id  = h.schedul_id
                                        LIMIT 1;");
        $getClase->execute([':student' => $student]);

        if ($getClase->rowCount() === 0) {
            return ['success' => false, 'message' => '&#x2718; Alumno no valido!'];
        }

        $clase = $getClase->fetch();

        // if ($clase->teacher_id !== $teacher) {
        //     return ['success' => false, 'message' => '&#x2718; Usted no es el profesor del alumno!'];
        // }

        $horario = date('g:i a', strtotime($clase->hour_init)) . ' - ' . date('g:i a', strtotime($clase->hour_end));
        $dias    = GeneralModel::getScheduleClass($clase->schedul_id);

        $commit   = true;
        $database->beginTransaction();
        try{
            $sql = $database->prepare("INSERT INTO students_absences(
                                                                    student_id, 
                                                                    course, 
                                                                    horary, 
                                                                    teacher_id,
                                                                    absence_note, 
                                                                    teacher_note,
                                                                    absence_date,
                                                                    contact_date,
                                                                    return_date,
                                                                    created_by
                                                                ) 
                                                        VALUES(
                                                            :student,
                                                            :course,
                                                            :horary,
                                                            :teacher,
                                                            :absence_note,
                                                            :teacher_note,
                                                            :absence_date,
                                                            :contact_date,
                                                            :return_date,
                                                            :created_by
                                                        );");
            $sql->execute([
                ':student'      => $student,
                ':course'      => $clase->course.' '.$clase->group_name,
                ':horary'       => $dias.'&'.$horario,
                ':teacher'      => $teacher,
                ':absence_note' => $absence_note,
                ':teacher_note' => $comment,
                ':absence_date' => $absence_date,
                ':contact_date' => $contact_date,
                ':return_date'  => $return_date,
                ':created_by'   => Session::get('user_id')
            ]);

            $commit = $sql->rowCount() > 0;
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                        'success' => false, 
                        'message' => '&#x2718; Error desconocido, intente de nuevo o reporte el error!'
                    );
        }else {
            $database->commit();
            return array(
                        'success' => true, 
                        'message' => '&#x2713; Registro guardado correctamente!!'
                    );
        }
    }

    public static function updateAbsence($absence, $absence_date, $teacher, $teacher_note, $absence_note, $contact_date, $return_date){
        $database = DatabaseFactory::getFactory()->getConnection();

        // Get the current absence info, to known if updated it or not
        $getAbsence = $database->prepare("SELECT absence_note, contact_date, return_date 
                                        FROM students_absences 
                                        WHERE absence_id = :absence LIMIT 1;");
        $getAbsence->execute([':absence' => $absence]);
        $absenceInfo = $getAbsence->fetch();

        if ($absenceInfo->absence_note !== null && $absence_note === null) {
            $absence_note = $absenceInfo->absence_note;
        }
        if ($absenceInfo->contact_date !== null && $contact_date === null) {
            $contact_date = $absenceInfo->contact_date;
        }

        if($absenceInfo->return_date !== null && $return_date === null){
            $return_date  = $absenceInfo->return_date;
        }


        $commit   = true;
        $database->beginTransaction();
        try{
            $sql =  $database->prepare("UPDATE students_absences
                                        SET teacher_id   = :teacher, 
                                            teacher_note = :teacher_note,
                                            absence_date = :absence_date,
                                            absence_note = :absence_note,
                                            contact_date = :contact_date,
                                            return_date  = :return_date
                                        WHERE absence_id = :absence;");
            $commit = $sql->execute([
                ':teacher'      => $teacher,
                ':teacher_note' => $teacher_note,
                ':absence_date' => $absence_date,
                ':absence_note' => $absence_note,
                ':contact_date' => $contact_date,
                ':return_date'  => $return_date,
                ':absence'      => $absence
            ]);            
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                        'success' => false, 
                        'message' => '&#x2718; Error desconocido, intente de nuevo o reporte el error!'
                    );
        }else {
            $database->commit();
            return array(
                        'success' => true, 
                        'message' => '&#x2713; Registro Actualizado correctamente!!'
                    );
        }
    }

    public static function findTeacherByStudent($student){
        $database = DatabaseFactory::getFactory()->getConnection();

        $getTeacher = $database->prepare("SELECT c.teacher_id 
                                          FROM students_groups as sg, classes as c 
                                          WHERE sg.student_id = :student
                                            AND sg.class_id   = c.class_id
                                          LIMIT 1;");
        $getTeacher->execute([':student' => $student]);

        if ($getTeacher->rowCount() > 0) {
            $teacher = $getTeacher->fetch();
            return ['success' => true, 'teacher' => $teacher->teacher_id];
        }

        return ['success' => false, 'message' => 'teacher not found!'];
    }

    public static function deleteAbsence($absence){
        $database = DatabaseFactory::getFactory()->getConnection();

        $commit   = true;
        $database->beginTransaction();
        try{
            $sql =  $database->prepare("UPDATE students_absences
                                        SET status   = 0
                                        WHERE absence_id = :absence;");
            $commit = $sql->execute([':absence' => $absence]);            
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                        'success' => false, 
                        'message' => '&#x2718; Error desconocido, intente de nuevo o reporte el error!'
                    );
        }else {
            $database->commit();
            return array(
                        'success' => true, 
                        'message' => '&#x2713; Registro Eliminado correctamente!!'
                    );
        }
    }




    /**
    |===============================================================================================
    | D E L E T E   S T U D E N T S  [ELIMINAR]
    |=============================================================================================== 
    */
   
    //We don´t erase the student info from de DB, just give a deleted status
    public static function tableDeletedStudents(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = GeneralModel::allStudentsDeleted();

        if ($students !== null) {

            $datos = [];
            $counter = 1;
            foreach ($students as $alumno) {
                $id_grupo = 0;
                $grupo = 'Sin Grupo';

                if ($alumno->class_id !== NULL) {
                    $clase = $database->prepare("SELECT c.class_id, c.course_id, CONCAT_WS(' ', cu.course, g.group_name) as grupo
                                                 FROM classes as c, courses as cu, groups as g
                                                 WHERE c.class_id  = :clase
                                                   AND c.status    IN (1,2)
                                                   AND c.course_id = cu.course_id
                                                   AND c.group_id  = g.group_id
                                                 LIMIT 1;");
                    $clase->execute(array(':clase' => $alumno->class_id));
                    if ($clase->rowCount() > 0) {
                        $clase = $clase->fetch();
                        $id_grupo = $clase->class_id;
                        $grupo = $clase->grupo;
                    }
                }

                //-> Tutor del Alumno
                $id_tutor     = 0;
                $nombre_tutor = 'N/A';
                if ($alumno->id_tutor !== NULL) {
                    $tutor = $database->prepare("SELECT id_tutor, CONCAT_WS(' ', namet, surnamet, lastnamet) as name
                                                    FROM tutors
                                                    WHERE id_tutor = :tutor
                                                 LIMIT 1;");
                    $tutor->execute(array(':tutor' => $alumno->id_tutor));
                    if ($tutor->rowCount() > 0) {
                        $tutor = $tutor->fetch();
                        $id_tutor = $tutor->id_tutor;
                        $nombre_tutor = $tutor->name;
                    }
                }

                $url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').$alumno->avatar;

                if (!file_exists($url)) {
                    $url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').strtolower($alumno->genre).'.jpg';
                }
                $avatar = '<img class="rounded-circle" src="'.$url.'" alt="foto" widt="42" height="42">';
                $boton = '<button type="button"
                                  data-student="'.$alumno->student_id.'"
                                  data-name="'.$alumno->name.'"
                                  data-group="'.$id_grupo.'"
                                  class="btn btn-sm btn-outline-info reactive_student">Reactivar <i class="fa fa-refresh"></i></button>';

                $info = array(
                    'count'   => $counter,
                    'name'    => $alumno->name,
                    'age'     => $alumno->age,
                    'genre'   => $alumno->genre,
                    'avatar'  => $avatar,
                    'studies' => $alumno->studies.' '.$alumno->lastgrade,
                    'group'   => $grupo,
                    'tutor'   => $nombre_tutor,
                    'edit'    => $boton
                );

                array_push($datos, $info);
                $counter++;
            }

            return array('data' => $datos);
        }

        return null;    
    }

    public static function deleteStudent($student){
        $database  = DatabaseFactory::getFactory()->getConnection();
        $commit    = true;
        $timestamp = H::getTime();
        $database->beginTransaction();
        try{
            $query = $database->prepare("UPDATE students 
                                         SET deleted = 1, 
                                             deleted_at = :today 
                                         WHERE student_id = :student;");
            $deleted = $query->execute(array(':student' => $student, ':today' => $timestamp));

            if ($deleted) {
                $update = $database->prepare("UPDATE students_groups 
                                              SET status = 0
                                              WHERE student_id = :student;");
                $update->execute(array(':student' => $student));
            } else {
                $commit = false;
            }
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                        'success' => false, 
                        'message' => '&#x2718; No se pudo eliminar al alumno, intente de nuevo o reporte el error!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Alumno eliminado correctamente!!');
        }
    }

    // Verificar si la clase aun esta activa; de lo contrario retornar al alumno a lista de espera
    public static function validateClass($classId){
        $database  = DatabaseFactory::getFactory()->getConnection();

        $groupToReturn = 'Lista en Espera';
        $groupId = 0;
        $find = $database->prepare("SELECT CONCAT_WS(' ',c.course, g.group_name) as grupo
                                    FROM classes as cl, courses as c, groups as g
                                    WHERE cl.class_id = :clase
                                      AND cl.course_id = c.course_id
                                      AND cl.group_id  = g.group_id
                                      AND cl.status    != 3
                                    LIMIT 1;");
        $find->execute(array(':clase' => $classId));

        if ($find->rowCount() > 0) {
            $groupToReturn = $find->fetch()->grupo;
            $groupId = (int)$classId;
        }

        return array('listStudents' => $groupToReturn, 'classId' => $groupId);
    }

    public static function restoreStudent($student, $class){
        $database  = DatabaseFactory::getFactory()->getConnection();
        $commit    = true;
        $timestamp = H::getTime();
        $database->beginTransaction();
        try{
            $query = $database->prepare("UPDATE students 
                                         SET deleted = 0, 
                                             deleted_at = :today 
                                         WHERE student_id = :student;");
            $deleted = $query->execute(array(':student' => $student, ':today' => $timestamp));

            if ($deleted) {
                $update = $database->prepare("UPDATE students_groups 
                                              SET status = :clase
                                              WHERE student_id = :student;");
                $update->execute(array(':student' => $student, ':clase' => $class));
            } else {
                $commit = false;
            }
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                        'success' => false, 
                        'message' => '&#x2718; ERROR:500, intente de nuevo o reporte el error!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Alumno reactivado correctamente!!');
        }

        

        return 0;
    }

    public static function deleteStudents($students){
        $database  = DatabaseFactory::getFactory()->getConnection();
        $timestamp = H::getTime();
        $commit    = true;
        $database->beginTransaction();
        try {
            foreach ($students as $student) {
                $query = $database->prepare("UPDATE students 
                                             SET deleted = 1, 
                                                 deleted_at = :today 
                                             WHERE student_id = :student;");
                $deleted = $query->execute(array(':student' => $student, ':today' => $timestamp));

                if ($deleted) { //Si no se puede eliminar al alumno
                    $update = $database->prepare("UPDATE students_groups 
                                                  SET status = 0
                                                  WHERE student_id = :student;");
                    $update->execute(array(':student' => $student));
                } else {
                    $commit = false;
                    break;
                }
            }          
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                    'success' => false, 
                    'message' => '&#x2718; Es probable que algunos alumnos no se hayan eliminado.'
                   );
        }else {
            $database->commit();
            return array(
                    'success' => true, 
                    'message' => '&#x2713; Alumnos eliminados correctamente!!');
        }

        $success = 1;
            

        return $success;
    }






    public static function template(){
        $commit   = true;
        $database->beginTransaction();
        try{
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
                        'success' => false, 
                        'message' => '&#x2718; No se realizo el cambio de grupo, intente de nuevo o reporte el error!'
                    );
        }else {
            $database->commit();
            return array(
                        'success' => true, 
                        'message' => '&#x2713; Cambio de grupo realizado correctamente!!'
                    );
        }
    }
}
