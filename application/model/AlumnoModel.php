<?php

class AlumnoModel
{
    public static function tableStudents($course, $page){
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
        if ($students !== null) {
            H::getLibrary('paginadorLib');
            $paginate = new \Paginador();
            $filas    = 20;
            $page     = (int)$page;
            $alumnos  = $paginate->paginar($students, $page, $filas);
            $counter  = $page > 0 ? (($page*$filas)-$filas) + 1 : 1;

            $datos = [];
            foreach ($alumnos as $alumno) {
                $id_grupo = 0;
                $grupo = '<a href="javascript:void(0)" class="link adding_group" data-student="'.$alumno->student_id.'"
                             title="Agregar grupo">Agregar a Grupo</a>';

                if ($alumno->class_id !== NULL) {
                    $clase = $database->prepare("SELECT c.class_id, c.course_id, cu.course, g.group_name
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
                        $nombre_curso = ucwords(strtolower($clase->course));
                        $grupo = '<a class="change_group"
                                     href="javascript:void(0)"
                                     data-student="'.$alumno->student_id.'"
                                     data-class="'.$clase->class_id.'"
                                     data-course="'.$clase->course_id.'"
                                     data-group="'.$nombre_curso.' '.$clase->group_name.'"
                                     title="Agregar grupo">'.$nombre_curso.' '.$clase->group_name.'</a>';
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

                $datos[$counter] = new stdClass();
                $datos[$counter]->count    = $counter;
                $datos[$counter]->id       = $alumno->student_id;
                $datos[$counter]->nombre   = $alumno->name;
                $datos[$counter]->apellido = $alumno->surname.' '.$alumno->lastname;
                $datos[$counter]->edad     = $alumno->age;
                $datos[$counter]->sexo     = $alumno->genre;
                $datos[$counter]->avatar   = $alumno->avatar;
                $datos[$counter]->estudios = $alumno->studies.' '.$alumno->lastgrade;
                $datos[$counter]->convenio = $alumno->convenio;
                $datos[$counter]->id_grupo = $id_grupo;
                $datos[$counter]->grupo    = $grupo;
                $datos[$counter]->id_tutor = $id_tutor;
                $datos[$counter]->tutor    = $nombre_tutor;
                $counter++;
            }

            // Pasa información a la vista
            $paginacion = $paginate->getView('pagination_ajax', 'students');
            echo $paginacion; 
            self::viewTableStudents($datos, $course);
            if (count($datos) > 15) {
            	echo $paginacion;
            }
        } else {
            echo '<h4 class="text-center text-secondary my-3">
                    No hay alumnos en esta lista.
                  </h4>';
        }      
    }

    public static function viewTableStudents($alumnos, $curso){
        $u_type = Session::get('user_account_type');
        $show = $u_type === '1' || $u_type === '2'; //-> true or false
        if (count($alumnos) > 0) {
            $main_check =  '<div class="checkbox select_all"><label><span class="fa fa-arrow-up">
                                </span>
                                    <input type="checkbox" class="check_all" id="select_all_'.$curso.'" />
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            </div>';
            echo '<div class="table-responsive">';
                echo '<table id="tbl_students_'.$curso.'"
                             class="table table-striped table-sm table-bordered">';
                    echo '<thead>';
                        echo '<tr class="info">';
                            echo '<th class="text-center"> N° </th>';
                            echo '<th class="text-center">Foto</th>';
                            echo '<th class="text-center">Apellidos</th>';
                            echo '<th class="text-center">Nombre</th>';
                            echo '<th class="text-center">Escolaridad</th>';
                            echo '<th class="text-center">Edad</th>';
                            echo '<th class="text-center">Grupo</th>';
                            echo '<th class="text-center">Tutor</th>';
                            echo '<th class="text-center">Opciones</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        $c = 1;
                        foreach ($alumnos as $row) {
                            $r = $row->count < 10 ? '&nbsp;&nbsp;'.$row->count : $row->count;
                            $url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').$row->avatar;
                            $check = '<b>'.$r.'</b>';

                            if (!file_exists($url)) {
                            	$url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT').strtolower($row->sexo).'.jpg';
                            }
                            $avatar = '<img class="rounded-circle" src="'.$url.'" alt="foto" widt="42" height="42">';
                            $convenio = $row->convenio == "0" ?
                                            '<span data-toggle="tooltip" title="Convenio Pendiente" class="o-red fa fa-file-text-o"></span>' :
                                                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            if ($show) {
                            $check = '<div class="checkbox"><label><b>'.$r.'</b>
                                        <input type="checkbox" class="check_one" name="alumnos[]" value="'.$row->id.'" />
                                        </label>
                                        '.$convenio.'
                                      </div>';
                            }
                            echo '<tr class="active">';
                            echo '<td class="text-center">'.$check.'</td>';
                            echo '<td class="text-center">'.$avatar.'</td>';
                            echo '<td class="text-center txt">'.$row->apellido.'</td>';
                            echo '<td class="text-center txt">'.$row->nombre.'</td>';
                            echo '<td class="text-center txt">'.$row->estudios.'</td>';
                            echo '<td class="text-center txt">'.$row->edad.'</td>';
                            echo '<td class="text-center txt">'.$row->grupo.'</td>';
                            echo '<td class="text-center txt">'.$row->tutor.'</td>';
                            echo '<td class="text-center">';
                            echo '<div class="btn-group">';
                                echo '<a href="javascript:void(0)"
                                         data-target="#"
                                         class="btn btn-info btn-sm dropdown-toggle"
                                         data-toggle="dropdown">Más.. &nbsp;&nbsp; <span class="caret"></span>
                                      </a>';
                                echo '<ul class="dropdown-menu student">';
                                if ($show) {
                                echo '<li>
                                            <a href="'.Config::get('URL').'alumno/perfil/'.$row->id.'"
                                               data-student="'.$row->id.'"
                                               data-tutor="'.$row->id_tutor.'"
                                               data-clase="'.$row->id_grupo.'"
                                               data-curso="'.$curso.'">
                                                <span class="text-dark" data-feather="chevron-right"></span>
                                                Perfil
                                            </a>
                                        </li>';
                                echo    '<li>
                                            <a href="'.Config::get('URL').'alumno/convenio">
                                                <span class="text-primary" data-feather="chevron-right"></span>
                                                Convenio
                                            </a></li>';
                                echo    '<li>
                                            <a href="javascript:void(0)">
                                                <span class="text-info" data-feather="chevron-right"></span>
                                                Cambiar Foto
                                            </a>
                                        </li>';
                                }
                                echo   '<li>
                                            <a href="'.Config::get('URL').'evaluaciones/index/'.$row->id.'">
                                                <span class="text-success" data-feather="chevron-right"></span>
                                                Calificaciones
                                            </a>
                                        </li>';
                                echo   '<li>
                                            <a  href="javascript:void(0)" 
                                                class="btnDeleteStudent" 
                                                id="'.$row->id.'"
                                                data-name="'.$row->nombre.' '.$row->apellido.'">
                                                <span class="text-secondary" data-feather="chevron-right"></span>
                                                Eliminar
                                            </a>
                                        </li>';
                                echo '</ul>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                            $c++;
                        }
                    echo '</tbody>';
                    if ($u_type === '1' || $u_type === '2') {
                    echo '<tfoot>';
                    echo '<tr>';
                    echo '<td class="text-center">'.$main_check.'</td>';
                    echo '<td class="text-center">
                          </td>';
                    echo '<td class="text-center">
                            <button type="button" class="btn btn-sm mini btn-info change_multi">
                                Cambiar de Grupo
                            </button>
                          </td>';
                    echo '<td class="text-center">
                            <button type="button" class="btn btn-sm mini btn-warning tekedown_multi">
                                Dar De Baja
                            </button>
                          </td>';
                    echo '<td class="text-center">
                            <button type="button" class="btn btn-sm mini btn-danger delete_multi">
                                Eliminar
                            </button>
                          </td>';
                    echo '<td class="text-center">
                          </td>';
                    echo '<td class="text-center">
                            <button type="button" class="btn btn-sm mini btn-secondary invoice_list">
                                Facturación
                            </button>
                          </td>';
                    echo '<td class="text-center">
                          </td>';
                    echo '<td class="text-center">
                          </td>';
                    echo '</tr>';
                    echo '</tfoot>';
                    }
                echo '</table>';
            echo '</div>';
        } else {
            echo '<h4 class="text-center text-naatik subheader">No hay Alumnos inscritos en este nivel.</h4>';
        }
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

    // Grupos por curso
    public static function getGroupsByCourse($course) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $_sql = $database->prepare("SELECT c.class_id, c.group_id, g.group_name
                                    FROM classes as c, groups as g
                                    WHERE c.course_id = :course
                                      AND c.group_id  = g.group_id;");
        $_sql->execute(array(':course' => $course));

        if($_sql->rowCount() > 0){
            return $_sql->fetchAll();
        }
        return null;
    }









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

            $url_avatar = Config::get('URL').Config::get('PATH_AVATAR_STUDENT');

            $avatar = $url_avatar . strtolower($alumno->genre) . '.jpg';
            if (file_exists($url_avatar.$alumno->avatar)) {
                $avatar = $url_avatar . strtolower($alumno->avatar);
            }

            $alumno->avatar = $avatar;
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
            if ($alumno->id_tutor !== 0) {
                $address_owner = $alumno->id_tutor;
                $owner_type    = 1;
            }

            $_sql = $database->prepare("SELECT * FROM address WHERE user_id = :user AND user_type = :type;");
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
            $sql =  $database->prepare("SELECT id_tutor, namet, surnamet, lastnamet,
                                               job, cellphone, phone, relationship,
                                               phone_alt, relationship_alt
                                        FROM tutors
                                        WHERE id_tutor = :tutor
                                        LIMIT 1;");

            $sql->execute(array(':tutor' => $tutor));

            if ($sql->rowCount() > 0) {
                return $sql->fetch();
            }
        }

        return null;
    }

    public static function studiesProfileData($student){
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT sd.*, sg.class_id
                                     FROM students_details as sd, students_groups as sg
                                     WHERE sd.student_id = :student
                                       AND sg.student_id = :student
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

            H::p($details);
            exit();

            return $details;
        }

        return null;
    }





    public static function getGroups($course) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $_sql = $database->prepare("SELECT c.class_id, c.group_id, g.group_name
					                FROM classes as c, groups as g
					                WHERE c.course_id = :course
					                  AND c.group_id  = g.group_id
					                  AND c.status = 1;");
        $_sql->execute(array(':course' => $course));

        if($_sql->rowCount() > 0){
            return $_sql->fetchAll();
        }
        return null;
    }

    public static function AddStudentToClass($alumno, $clase){
        $database = DatabaseFactory::getFactory()->getConnection();

        $update = $database->prepare("UPDATE students_groups 
                                      SET class_id = :clase 
                                      WHERE student_id = :alumno;");
        $update = $update->execute(array(':clase' => $clase, ':alumno' => $alumno));

        if ($update) {
            echo 1;
        } else {
            echo 0;
        }
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





    public static function ChangeStudentGroup($alumno, $clase){
        $database = DatabaseFactory::getFactory()->getConnection();

        $clase === '0' ? $clase = NULL : $clase = (int)$clase;
        $change = $database->prepare("UPDATE students_groups SET class_id = :clase WHERE student_id = :alumno;");
        $save = $change->execute(array(':clase' => $clase, ':alumno' => $alumno));
        if ($save) {
            if ($clase === NULL) {
                $update_status = $database->prepare("UPDATE students SET status = 2 WHERE student_id = :student;");
                $update_status->execute(array(':student' => $alumno));
            } else {
                $update_status = $database->prepare("UPDATE students SET status = 1 WHERE student_id = :student;");
                $update_status->execute(array(':student' => $alumno));
            }
            echo 1;
        } else {
            echo 2;
        }

    }

    public static function ChangeStudentsGroup($alumnos, $clase){
        $database = DatabaseFactory::getFactory()->getConnection();

        $clase === '0' ? $clase = NULL : $clase = (int)$clase;
        $count = 0;
        foreach ($alumnos as $alumno) {
            $change = $database->prepare("UPDATE students_groups SET class_id = :clase WHERE student_id = :alumno;");
            $save = $change->execute(array(':clase' => $clase, ':alumno' => $alumno));
            if (!$save) {
                $count++;
            }
        }

        echo $count === 0 ? 1 : 2;
    }




    public static function tableInactiveStudents(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = GeneralModel::allStudentsDown();

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

                $info = array(
                    'count' => $counter,
                    'name'  => $alumno->name,
                    'age'   => $alumno->age,
                    'genre' => $alumno->genre,
                    'avatar' => $avatar,
                    'studies' => $alumno->studies.' '.$alumno->lastgrade,
                    'group'   => $grupo,
                    'tutor'   => $nombre_tutor
                );

                array_push($datos, $info);
                $counter++;
            }

            return array('data' => $datos);
        }

        return null;    
    }

    public static function checkOutStudent($alumno, $estado) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $fecha = H::getTime('Y-m-d');

        $checkout = $database->prepare("UPDATE academic_data
                                        SET baja = :estado,
                                            fecha_baja = :fecha
                                        WHERE student_id = :alumno;");
        $update = $checkout->execute(array(':estado' => $estado, ':fecha' => $fecha, ':alumno' => $alumno));

        if ($update && $estado === 0) {
            $state = $database->prepare("UPDATE students_groups SET state = 0 WHERE student_id = :alumno");
            $state->execute(array(':alumno' => $alumno));
        }

        echo $update ? 1 : 0;
    }

    public static function getStudentsCheckout(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT s.*, ad.studies, ad.last_grade, g.class_id
                FROM students as s,
                     academic_data as ad,
                     groups as g
                WHERE s.student_id = ad.student_id
                  AND ad.baja      = 1
                  AND s.student_id = g.student_id";

        $query = $database->prepare($sql);
        $query->execute();

        if ($query->rowCount() > 0) {
            $students = $query->fetchAll();

            $alumos = array();
            foreach ($students as $row) {
                $id_tutor = 0;
                $tutor = '- - -';
                $curso = '<a class="link adding_group"
                             data-student="'.$row->student_id.'"
                             data-toggle="modal"
                             data-target="#add_to_group"
                             title="Agregar grupo"><strong>Agregar a Grupo</strong></a>';
                $clase = 0;

                if ($row->id_tutor !== '0') {
                    $getTutor = $database->prepare("SELECT id_tutor, namet, surname1, surname2
                                                    FROM tutors WHERE id_tutor = :tutor
                                                    LIMIT 1;");
                    $getTutor->execute(array(':tutor' => $row->id_tutor));
                    if ($getTutor->rowCount() > 0) {
                        $info = $getTutor->fetch();
                        $id_tutor = $info->id_tutor;
                        $tutor = ucwords(strtolower($info->namet)).' '.ucwords(strtolower($info->surname1));
                    }
                }

                if ($row->class_id !== NULL) {
                    $qry = $database->prepare("SELECT c.id as clase,
                                                      cu.id as course,
                                                      l.id as grupo,
                                                      cu.name,
                                                      l.level
                                               FROM classes as c, courses as cu, levels as l
                                               WHERE c.id = :clase
                                                 AND c.id_course = cu.id
                                                 AND c.id_level  = l.id
                                               LIMIT 1;");
                    $qry->execute(array(':clase' => $row->class_id));

                    if ($qry->rowCount() > 0) {
                        $fila = $qry->fetch();
                        $clase = $fila->clase;
                        $curso = '<a class="link change_group"
                                     data-student="'.$row->student_id.'"
                                     data-group="'.$fila->grupo.'"
                                     data-course="'.$fila->course.'"
                                     data-clase="'.$fila->clase.'"
                                     title="Cambiar grupo">'.$fila->name.' '.$fila->level.'</a>';

                    }
                }

                $alumnos[$row->student_id] = new stdClass();
                $alumnos[$row->student_id]->id = $row->student_id;
                $alumnos[$row->student_id]->name      = ucwords(strtolower($row->name));
                $alumnos[$row->student_id]->surname   = ucwords(strtolower($row->surname));
                $alumnos[$row->student_id]->lastname  = ucwords(strtolower($row->lastname));
                $alumnos[$row->student_id]->avatar    = $row->avatar;
                $alumnos[$row->student_id]->tutor_id  = $id_tutor;
                $alumnos[$row->student_id]->tutor     = $tutor;
                $alumnos[$row->student_id]->birthdays = $row->birthday;
                $alumnos[$row->student_id]->age   = $row->age;
                $alumnos[$row->student_id]->genre = $row->genre;
                $alumnos[$row->student_id]->clase = $clase;
                $alumnos[$row->student_id]->course= $curso;
                $alumnos[$row->student_id]->study = $row->studies;
                $alumnos[$row->student_id]->grade = $row->last_grade;
            }
            self::displayStudentsCheckout($alumnos);
        } else {
            echo '<h4 class="text-center text-primary subheader">No hay Alumnos de baja.</h4>';
        }
    }

    public static function displayStudentsCheckout($alumnos){
        if (count($alumnos) > 0) {
            echo '<div class="table-responsive">';
                echo '<table id="tbl_checkout"
                             class="table table-bordered table-hover">';
                    echo '<thead>';
                        echo '<tr class="info">';
                            echo '<th class="text-center">Foto</th>';
                            echo '<th class="text-center">Apellidos</th>';
                            echo '<th class="text-center">Nombre</th>';
                            echo '<th class="text-center">Edad</th>';
                            echo '<th class="text-center">Escolaridad</th>';
                            echo '<th class="text-center">Grupo</th>';
                            echo '<th class="text-center">Tutor</th>';
                            echo '<th class="text-center">Opciones</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach ($alumnos as $row) {
                            $url = Config::get('URL').Config::get('PATH_AVATAR_STUDENT');
                            $avatar = '<img class="foto-mini" src="'.$url.$row->avatar.'.jpg" alt="avatar">';
                            echo '<tr class="row_data">';
                            echo '<td class="text-center">'.$avatar.'</td>';
                            echo '<td class="text-center txt">'.$row->surname.' '.$row->lastname.'</td>';
                            echo '<td class="text-center txt">'.$row->name.'</td>';
                            echo '<td class="text-center txt">'.$row->age.'</td>';
                            echo '<td class="text-center txt">'.$row->study.'</td>';
                            echo '<td class="text-center txt">'.$row->course.'</td>';
                            echo '<td class="text-center txt">'.$row->tutor.'</td>';
                            echo '<td class="text-center">
                                    <div class="btn-group">

                                      <a href="javascript:void(0)"
                                         data-target="#"
                                         class="btn btn-main btn-xs btn-raised dropdown-toggle"
                                         data-toggle="dropdown">Más.. &nbsp;&nbsp; <span class="caret"></span></a>
                                      <ul class="dropdown-menu student">
                                        <li>
                                            <a href="'.Config::get('URL').'alumno/perfilAlumno/'.$row->id.'"
                                               data-student="'.$row->id.'"
                                               data-tutor="'.$row->tutor_id.'"
                                               data-clase="'.$row->clase.'">
                                                <span class="o-blue glyphicon glyphicon-record"></span> Detalles</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)"
                                               class="checkin_student"
                                               data-alumno="'.$row->id.'"
                                               data-nombre="'.$row->name.' '.$row->surname.'">
                                                    <span class="o-purple glyphicon glyphicon-record"></span>
                                                    Dar de Alta
                                            </a>
                                        </li>
                                      </ul>
                                    </div>
                                 </td>';
                            echo '</tr>';
                        }
                    echo '</tbody>';
                echo '</table>';
                echo "<br><br><br>";
            echo '</div>';
        } else {
            echo '<h4 class="text-center text-primary subheader">No hay Alumnos de baja.</h4>';
        }
    }



    ////////////////////////////////////////////////////////////////////
    //= = = = = = = C R E A T E   N E W   S T U D E N T = = = = = = = //
    ////////////////////////////////////////////////////////////////////

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

    public static function createStudent($tutor, $address, $surname, $lastname, $name, $birthday, $age, 
								    	 $genre, $civil_status, $cellphone, $reference, $sickness, 
								    	 $medication, $comment, $invoice, $homestay, $acta,
								    	 $street, $number, $between, $colony)
    {
    	$database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $student  = null;

        $name     = ucwords(strtolower($name));
        $surname  = ucwords(strtolower($surname));
        $lastname = ucwords(strtolower($lastname));
        $tutor    = (int)$tutor;

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
	            $student = $database->lastInsertId();
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
    	$avatar = $database->prepare('UPDATE students SET avatar = :avatar WHERE student_id = :student;');
    	$save = $avatar->execute(array(':avatar' => $avatar_name, ':student' => $student));

    	if ($save) {
    		return array('success' => true, 'message' => 'Se guardo correctamente datos del Alumno');
    	}
    	return array('success' => false, 'message' => 'No se guardo la foto del alumno, subalo mas tarde.');
    }

    public static function createStudies($student, $ocupation, $workplace, $studies, $lastgrade, $prior_course, $prior_comments, $class_id, $date_start){
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
	            		'student' => $student,
	            		'message' => 'Registro finalizado');
        }
    }









    public static function updateTutor($tutor){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;

        $database->beginTransaction();
        try{
            $update  =  $database->prepare("UPDATE tutors
                                            SET namet        = :name,
                                                surnamet     = :surname,
                                                lastnamet    = :lastname,
                                                job          = :job,
                                                phone        = :phone,
                                                cellphone    = :cellphone,
                                                relationship = :relation,
                                                phone_alt      = :phone_alt,
                                                relationship_alt = :relation_alt
                                            WHERE id_tutor = :tutor");
            $update= $update->execute(array(':name'         => ucwords(strtolower($tutor['name'])),
                                            ':surname'      => ucwords(strtolower($tutor['surname'])),
                                            ':lastname'     => ucwords(strtolower($tutor['lastname'])),
                                            ':job'          => $tutor['ocupation'],
                                            ':phone'        => $tutor['phone'],
                                            ':cellphone'    => $tutor['cellphone'],
                                            ':relation'     => $tutor['relationship'],
                                            ':phone_alt'    => $tutor['phone_alt'],
                                            ':relation_alt' => $tutor['relation_alt'],
                                            ':tutor'        => $tutor['tutor_id']));
            if (!$update) {
                $commit = false;
            }
            
        }catch (PDOException $e) {
            $commit = false;
        }

        $updateAddress = self::updateAddress($tutor['tutor_id'], Session::get('address'), 1);

        if (!$commit || !$updateAddress) {
            $database->rollBack();
            Session::add('feedback_negative','Error al actualizar tutor!');
            return false;
        }else {
            $database->commit();
            return (int)$tutor['tutor_id'];
        }
    }

    public static function saveAddress($user, $address, $user_type){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;

        $database->beginTransaction();
        try{
            $sql = "INSERT INTO address(user_id, user_type, street, st_number, st_between, colony,
                                        city, zipcode, state, country, latitud, longitud)
                                VALUES(:user, :user_type, :street, :st_number, :st_between, :colony,
                                       :city, :zipcode, :state, :country, :latitud, :longitud);";
            $query = $database->prepare($sql);
            $query->execute(array(
                ':user'       => $user,
                ':user_type'  => $user_type,
                ':street'     => $address['street'],
                ':st_number'  => $address['number'],
                ':st_between' => $address['between'],
                ':colony'     => $address['colony'],
                ':city'       => 'Felipe Carrillo Puerto',
                ':zipcode'    => 77200,
                ':state'      => 'Quintana Roo',
                ':country'    => 'México',
                ':latitud'    => $address['latitud'],
                ':longitud'   => $address['longitud']));

            if ($query->rowCount() < 1) {
                $commit = false;
            }            
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            Session::add('feedback_negative','Error al guardar direccion!');
            exit();
            return false;
        }else {
            $database->commit();
            return true;
        }
    }

    public static function updateAddress($user, $address, $user_type){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;

        $database->beginTransaction();
        try{
            $sql = "UPDATE address 
                    SET street     = :street, 
                        st_number  = :st_number, 
                        st_between = :st_between, 
                        colony     = :colony,
                        city       = :city, 
                        zipcode    = :zipcode, 
                        state      = :state, 
                        country    = :country, 
                        latitud    = :latitud, 
                        longitud   = :longitud
                    WHERE user_id   = :user 
                      AND user_type = :user_type;";
            $query = $database->prepare($sql);
            $query->execute(array(
                ':user'       => $user,
                ':user_type'  => $user_type,
                ':street'     => $address['street'],
                ':st_number'  => $address['number'],
                ':st_between' => $address['between'],
                ':colony'     => $address['colony'],
                ':city'       => 'Felipe Carrillo Puerto',
                ':zipcode'    => 77200,
                ':state'      => 'Quintana Roo',
                ':country'    => 'México',
                ':latitud'    => $address['latitud'],
                ':longitud'   => $address['longitud']));

            if ($query->rowCount() < 1) {
                $commit = false;
            }            
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            Session::add('feedback_negative','Error al actualizar!');
            return false;
        }else {
            $database->commit();
            return true;
        }
    }




    /////////////////////////////////////////////////////////
    // =  = = = = = = = = UPDATE STUDENTS DATA = = = = = = //
    /////////////////////////////////////////////////////////

    public static function updateStudentData($student_id, $tutor, $name, $surname, $lastname, $birthdate, $genre, $edo_civil, $cellphone, $reference, $street, $number, $between, $colony, $sickness, $medication, $homestay, $acta, $invoice, $comentario) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = ucwords(strtolower($name));
        $surname  = ucwords(strtolower($surname));
        $lastname = ucwords(strtolower($lastname));
        $age      = H::getAge($birthdate);

        if ($tutor !== 0) {
            $user = $tutor;
            $typo = 1;
        } else {
            $user = $student_id;
            $typo = 2;
        }

        $sql = $database->prepare("UPDATE students SET name       = :name,
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
                                    WHERE student_id = :student_id;");
        $update  =  $sql->execute(array(':name'      => $name,
                                        ':surname'   => $surname,
                                        ':lastname'  => $lastname,
                                        ':birthdate' => $birthdate,
                                        ':age'       => $age,
                                        ':genre'     => $genre,
                                        ':edo_civil' => $edo_civil,
                                        ':cellphone' => $cellphone,
                                        ':reference' => $reference,
                                        ':sickness'  => $sickness,
                                        ':medication' => $medication,
                                        ':comentario' => $comentario,
                                        ':student_id' => $student_id));

        if ($update) {
            $set_up  =  $database->prepare("UPDATE address
                                            SET street     = :calle,
                                                st_number  = :numero,
                                                st_between = :entre,
                                                reference  = :referencia,
                                                colony     = :colonia
                                            WHERE user_id  = :user
                                              AND user_type = :tipo;");
            $set_up->execute(array(':calle'  => $street,
                                   ':numero' => $number,
                                   ':entre'  => $between,
                                   ':referencia' => $reference,
                                   'colonia' => $colony,
                                   ':user'   => $user,
                                   ':tipo'   => $typo));

            $set_details =  $database->prepare("UPDATE students_details
                                                SET facturacion     = :factura,
                                                    homestay        = :homestay,
                                                    acta_nacimiento = :acta
                                                WHERE id_student = :student;");
            $set_details->execute(array(':factura'  => $invoice,
                                        ':homestay' => $homestay,
                                        ':acta'     => $acta,
                                        ':student'  => $student_id));
            Session::add('feedback_positive', "Datos del Alumno actualizados correctamente");
        } else {
            Session::add('feedback_negative', "Error al actualizar, intente de nuevo por favor!");
        }
    }

    public static function updateTutorData($tutor, $name, $surname, $lastname, $job, $relationship, $phone, $cellphone, $relation_alt, $phone_alt){
        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = ucwords(strtolower($name));
        $surname  = ucwords(strtolower($surname));
        $lastname = ucwords(strtolower($lastname));

        $update  =  $database->prepare("UPDATE tutors
                                        SET namet     = :name,
                                            surnamet  = :surname,
                                            lastnamet = :lastname,
                                            job       = :job,
                                            phone     = :phone,
                                            cellphone = :cellphone,
                                            relationship = :relation,
                                            phone_alt      = :phone_alt,
                                            relationship_alt = :relation_alt
                                        WHERE id_tutor = :tutor");
        $update= $update->execute(array(':name'         => $name,
                                        ':surname'      => $surname,
                                        ':lastname'     => $lastname,
                                        ':job'          => $job,
                                        ':phone'        => $phorene,
                                        ':cellphone'    => $cellphone,
                                        ':relation'     => $relationship,
                                        ':phone_alt'    => $phone_alt,
                                        ':relation_alt' => $relation_alt,
                                        ':tutor'        => $tutor));
        if ($update) {
            Session::add('feedback_positive', 'Datos del Tutor actualizados correctamente.');
        } else {
            Session::add('feedback_negative', "Error al actualizar, intente de nuevo por favor!");
        }
    }

    public static function updateAcademicData($alumno, $ocupacion, $lugar_trabajo, $estudios, $grado){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql =  $database->prepare("UPDATE students_details
                                    SET ocupation  = :ocupacion,
                                        workplace  = :lugar,
                                        studies    = :estudios,
                                        lastgrade  = :grado
                                    WHERE student_id = :alumno;");
        $update  =  $sql->execute(array(':ocupacion' => $ocupacion,
                                        ':lugar'     => $lugar_trabajo,
                                        ':estudios'  => $estudios,
                                        ':grado'     => $grado,
                                        ':alumno'    => $alumno));

        if ($update) {
            Session::add('feedback_positive', "Datos Academicos actualizados correctamente");
        } else {
            Session::add('feedback_negative', "Error al actualizar, intente de nuevo por favor!");
        }
    }

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
                $boton = '<button type="button"
                                  data-student="'.$alumno->student_id.'" 
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
        $database = DatabaseFactory::getFactory()->getConnection();

        $timestamp = H::getTime();
        $query = $database->prepare("UPDATE students 
                                     SET deleted = 1, 
                                         deleted_at = :today 
                                     WHERE student_id = :student;");
        $deleted = $query->execute(array(':student' => $student, ':today' => $timestamp));

        if ($deleted) {
            $update = $database->prepare("UPDATE students_groups 
                                          SET state = 1, 
                                              deleted_at = :today 
                                          WHERE student_id = :student;");
            $update->execute(array(':student' => $student, ':today' => $timestamp));
            return 1;
        }

        return 0;
    }

    public static function deleteStudents($students){
        $database = DatabaseFactory::getFactory()->getConnection();

        $success = 1;
        foreach ($students as $student) {
            $query = $database->prepare("UPDATE students SET deleted = 1 WHERE student_id = :student;");
            $deleted = $query->execute(array(':student' => $student));

            if (!$deleted) { //Si no se puede eliminar al alumno
                $success = 0;
                return $success;
            }
        }

        return $success;
    }
}
