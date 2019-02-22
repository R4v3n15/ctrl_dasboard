<?php

class PagosModel
{
    public static function studentPayList($student, $year=null) {
        $database = DatabaseFactory::getFactory()->getConnection();

        // Setting year
        $year = $year === null ? date('Y') : $year;

        // SQL: student history pay in given year and ciclo
        $history =  $database->prepare("SELECT * FROM students_pays 
                                        WHERE student_id = :student
                                          AND year       = :year
                                        LIMIT 1;");
        $history->execute(array(':student' => $student,
                                ':year'    => $year));
        if ($history->rowCount() > 0) {
            return $history->fetch();
        }

        return null;
    }

    public static function getContactInfo($student, $tutor){
        if ((int)$tutor !== 0) {
            $database = DatabaseFactory::getFactory()->getConnection();
            $_sql = $database->prepare("SELECT cellphone, phone, phone_alt,
                                               CONCAT_WS(' ',namet, surnamet) as name
                                        FROM tutors
                                        WHERE id_tutor = :tutor
                                        LIMIT 1;");
            $_sql->execute(array(':tutor' => $tutor));
            if ($_sql->rowCount() > 0) {
                $row = $_sql->fetch();
                $phones = $row->name . '<br>';
                $phones .= 'Cel.: ' .   $row->cellphone . '<br>';
                if ($row->phone !== '' && $row->phone !== null) {
                $phones .= 'Casa: ' . $row->phone . '<br>';
                }
                // $phones .= 'Tel. Alt.:' .  $row->phone_alt;
                return $phones;
            } else {
                return '- - -';
            }
        }

        return 'N/A';
    }

    public static function getRelatives($student, $tutor) {
        if ((int)$tutor !== 0) {
            $database = DatabaseFactory::getFactory()->getConnection();
            $_sql = $database->prepare("SELECT CONCAT_WS(' ',name, surname) as relative
                                        FROM students
                                        WHERE id_tutor    = :tutor
                                          AND student_id != :student");
            $_sql->execute(array(':tutor' => $tutor, ':student' => $student));
            $relatives = '';
            if ($_sql->rowCount() > 0) {
                foreach ($_sql as $row) {
                    $relatives .= $row->relative . '<br>';
                }
                return $relatives;
            } else {
                return 'No';
            }
        }

        return 'NO';
    }

    public static function getRelatedInfo($student, $tutor) {
        if ((int)$tutor !== 0) {
            $database = DatabaseFactory::getFactory()->getConnection();
            $_sql = $database->prepare("SELECT s.student_id, 
                                               CONCAT_WS(' ', s.name, s.surname, s.lastname) as relative,
                                               cu.course,
                                               g.group_name
                                        FROM students as s, students_groups as sg, classes as c, courses as cu, groups as g
                                        WHERE s.id_tutor    = :tutor
                                          AND s.student_id != :student
                                          AND s.student_id = sg.student_id
                                          AND sg.class_id  = c.class_id
                                          AND c.course_id  = cu.course_id
                                          AND c.group_id   = g.group_id;");
            $_sql->execute(array(':tutor' => $tutor, ':student' => $student));
            $relatives = [];
            if ($_sql->rowCount() > 0) {
                foreach ($_sql as $row) {
                    array_push($relatives, array(
                                                'idStudent' => $row->student_id, 
                                                'name' => $row->relative,
                                                'group' => ucfirst(strtolower($row->course)) .' '. $row->group_name
                                            ));
                }
                return $relatives;
            }
        }

        return $relatives;
    }

    // Validate if student is scholar, return boolean value
    public static function getScholarStatus($student){
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT student_id 
                                    FROM becas 
                                    WHERE student_id = :student 
                                      AND status = 1 
                                    LIMIT 1;");
        $query->execute([':student' => $student]);
        return $query->rowCount() > 0;
    }

    public static function renderPayTable($course, $ciclo){
        $database = DatabaseFactory::getFactory()->getConnection();

        switch ($course) {
            case 'all': 
                    $students = GeneralModel::tablePayAll(); break;
            default: 
                    $curso = (int)$course;
                    $students = GeneralModel::tablePayByCourse($curso); break;
        }

        $datos = [];
        if ($students !== null) {
            $counter  = 1;

            foreach ($students as $alumno) {
                if ($alumno->class_id === NULL) {
                    continue;
                }

                $pagos = self::studentPayList($alumno->student_id);

                $comment = '<a href="javascript:void(0)" class="addComment" data-student="'.$alumno->student_id.'"
                                         data-comment="">Agregar <i class="fa fa-comment"></i></a></a>';

                $comentario = '';
                if ($pagos !== null && $pagos->comment !== null && $pagos->comment !== '') {
                    $comentario = $pagos->comment;
                    $comment = strlen($pagos->comment) > 85 ? substr($pagos->comment, 0, 85) :  $pagos->comment;
                    $comment .= '.. <a href="javascript:void(0)" class="addComment" 
                                    data-student="'.$alumno->student_id.'"
                                    data-comment="'.$pagos->comment.'"><i class="fa fa-edit"></i></a>';
                }

                $opt = '<a href="javascript:void(0)"
                            data-student="'.$alumno->student_id.'"
                            data-name="'.$alumno->name . ' ' . $alumno->surname.'"
                            data-comment="'.$comentario.'"
                            data-relatives="'.self::getRelatives($alumno->student_id, $alumno->id_tutor).'"
                            class="btn btn-sm btn-outline-primary btn-shadown btn-flat-sm payAction">
                            Pagar
                            </a>';


                if ($ciclo === "A") {
                    $_ago=0; $_sep=0; $_oct=0; $_nov=0; $_dic=0; $beca='';
                    
                    if ($pagos !== null) {
                        $_ago=$pagos->ago; 
                        $_sep=$pagos->sep; 
                        $_oct=$pagos->oct; 
                        $_nov=$pagos->nov; 
                        $_dic=$pagos->dic;

                        $beca = (int)$pagos->becado_a === 1 ? '<i class="mdi-action-beca">B</i><br>' : '';
                    }

                    $ago =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="ago"
                                        data-title="AGOSTO"
                                        data-status="'.$_ago.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_ago).'</a>';

                    $sep =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="sep"
                                        data-title="SEPTIEMBRE"
                                        data-status="'.$_sep.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_sep).'</a>';

                    $oct =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="oct"
                                        data-title="OCTUBRE"
                                        data-status="'.$_oct.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_oct).'</a>';

                    $nov =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="nov"
                                        data-title="NOVIEMBRE"
                                        data-status="'.$_nov.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_nov).'</a>';

                    $dic =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="dic"
                                        data-title="DICIEMBRE"
                                        data-status="'.$_dic.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_dic).'</a>';

                    
                    $info = array(
                        'count'      => $counter,
                        'student'    => $alumno->student_id,
                        'name'       => $alumno->name . ' ' . $alumno->surname,
                        'info'       => self::getContactInfo($alumno->student_id, $alumno->id_tutor),
                        'aug'        => $ago,
                        'sep'        => $sep,
                        'oct'        => $oct,
                        'nov'        => $nov,
                        'dec'        => $dic,
                        'comment'    => $comment,
                        'opt'        => $opt
                    );
                } else {
                    $_ene=0; $_feb=0; $_mar=0; $_abr=0; $_may=0; $_jun=0; $_jul=0; $beca='';

                    if ($pagos !== null) {
                        $_ene=$pagos->ene; 
                        $_feb=$pagos->feb; 
                        $_mar=$pagos->mar; 
                        $_abr=$pagos->abr; 
                        $_may=$pagos->may; 
                        $_jun=$pagos->jun; 
                        $_jul=$pagos->jul;

                        $beca = (int)$pagos->becado_b === 1 ? '<i class="mdi-action-beca">B</i><br>' : '';
                    }

                    $ene =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="ene"
                                        data-title="ENERO"
                                        data-status="'.$_ene.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_ene).'</a>';

                    $feb =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="feb"
                                        data-title="FEBRERO"
                                        data-status="'.$_feb.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_feb).'</a>';

                    $mar =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="mar"
                                        data-title="MARZO"
                                        data-status="'.$_mar.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_mar).'</a>';

                    $abr =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="abr"
                                        data-title="ABRIL"
                                        data-status="'.$_abr.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_abr).'</a>';

                    $may =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="may"
                                        data-title="MAYO"
                                        data-status="'.$_may.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_may).'</a>';

                    $jun =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="jun"
                                        data-title="JUNIO"
                                        data-status="'.$_jun.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_jun).'</a>';

                    $jul =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                        data-student="'.$alumno->student_id.'" 
                                        data-month="jul"
                                        data-title="JULIO"
                                        data-status="'.$_jul.'"
                                        data-name="'.$alumno->name.'">'.self::statusIcon($_jul).'</a>';

                    $info = array(
                        'count'      => $counter,
                        'student'    => $alumno->student_id,
                        'name'       => $alumno->name .' '.$alumno->surname,
                        'info'       => self::getContactInfo($alumno->student_id, $alumno->id_tutor),
                        'jan'        => $ene,
                        'feb'        => $feb,
                        'mar'        => $mar,
                        'apr'        => $abr,
                        'may'        => $may,
                        'jun'        => $jun,
                        'jul'        => $jul,
                        'comment'    => $comment,
                        'opt'        => $opt
                    );
                }

                array_push($datos, $info);
                $counter++;
            }
        }

        return array('data' => $datos);
    }


    public static function renderFullPayTable($course){
        $database = DatabaseFactory::getFactory()->getConnection();

        switch ($course) {
            case 'all': 
                    $students = GeneralModel::tablePayAll(); break;
            default: 
                    $curso = (int)$course;
                    $students = GeneralModel::tablePayByCourse($curso); break;
        }

        $datos = [];
        if ($students !== null) {
            $counter  = 1;

            foreach ($students as $alumno) {
                if ($alumno->class_id === NULL) {
                    continue;
                }

                $pagos = self::studentPayList($alumno->student_id);


                $comment = '<a href="javascript:void(0)" class="addComment" data-student="'.$alumno->student_id.'"
                                         data-comment="">Agregar <i class="fa fa-comment"></i></a></a>';

                $comentario = '';
                if ($pagos !== null && $pagos->comment !== null && $pagos->comment !== '') {
                    $comentario = $pagos->comment;
                    $comment = strlen($pagos->comment) > 85 ? substr($pagos->comment, 0, 85) :  $pagos->comment;
                    $comment .= '.. <a href="javascript:void(0)" class="addComment" 
                                    data-student="'.$alumno->student_id.'"
                                    data-comment="'.$pagos->comment.'"><i class="fa fa-edit"></i></a>';
                }

                $opt = '<a href="javascript:void(0)"
                            data-student="'.$alumno->student_id.'"
                            data-name="'.$alumno->name . ' ' . $alumno->surname.'"
                            data-tutor="'.$alumno->id_tutor.'"
                            data-comment="'.$comentario.'"
                            data-relatives="'.self::getRelatives($alumno->student_id, $alumno->id_tutor).'"
                            class="btn btn-sm btn-outline-primary btn-shadown payAction">
                            Pagar
                            </a>';

                // SET components
                $_ene=0; $_feb=0; $_mar=0; $_abr=0; $_may=0; $_jun=0; $_ago=0; $_sep=0; $_oct=0; $_nov=0; $_dic=0; $beca='';
                
                if ($pagos !== null) {
                    $_ene=$pagos->ene; 
                    $_feb=$pagos->feb; 
                    $_mar=$pagos->mar; 
                    $_abr=$pagos->abr; 
                    $_may=$pagos->may; 
                    $_jun=$pagos->jun;
                    $_ago=$pagos->ago; 
                    $_sep=$pagos->sep; 
                    $_oct=$pagos->oct; 
                    $_nov=$pagos->nov; 
                    $_dic=$pagos->dic;

                    $beca = self::getScholarStatus($alumno->student_id) ? '<i class="mdi-action-beca">B</i><br>' : '';
                }

                $ene =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="ene"
                                    data-title="ENERO"
                                    data-status="'.$_ene.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_ene).'</a>';

                $feb =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="feb"
                                    data-title="FEBRERO"
                                    data-status="'.$_feb.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_feb).'</a>';

                $mar =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="mar"
                                    data-title="MARZO"
                                    data-status="'.$_mar.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_mar).'</a>';

                $abr =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="abr"
                                    data-title="ABRIL"
                                    data-status="'.$_abr.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_abr).'</a>';

                $may =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="may"
                                    data-title="MAYO"
                                    data-status="'.$_may.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_may).'</a>';

                $jun =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="jun"
                                    data-title="JUNIO"
                                    data-status="'.$_jun.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_jun).'</a>';


                $ago =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="ago"
                                    data-title="AGOSTO"
                                    data-status="'.$_ago.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_ago).'</a>';

                $sep =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="sep"
                                    data-title="SEPTIEMBRE"
                                    data-status="'.$_sep.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_sep).'</a>';

                $oct =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="oct"
                                    data-title="OCTUBRE"
                                    data-status="'.$_oct.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_oct).'</a>';

                $nov =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="nov"
                                    data-title="NOVIEMBRE"
                                    data-status="'.$_nov.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_nov).'</a>';

                $dic =  $beca . '<a href="javascript:void(0)" class="payMonth" 
                                    data-student="'.$alumno->student_id.'" 
                                    data-month="dic"
                                    data-title="DICIEMBRE"
                                    data-status="'.$_dic.'"
                                    data-name="'.$alumno->name.'">'.self::statusIcon($_dic).'</a>';

                
                $info = array(
                    'count'      => $counter,
                    'student'    => $alumno->student_id,
                    'name'       => $alumno->name . ' ' . $alumno->surname . ' ' . $alumno->lastname,
                    'info'       => self::getContactInfo($alumno->student_id, $alumno->id_tutor),
                    'jan'        => $ene,
                    'feb'        => $feb,
                    'mar'        => $mar,
                    'apr'        => $abr,
                    'may'        => $may,
                    'jun'        => $jun,
                    'aug'        => $ago,
                    'sep'        => $sep,
                    'oct'        => $oct,
                    'nov'        => $nov,
                    'dec'        => $dic,
                    'comment'    => $comment,
                    'opt'        => $opt
                );

                array_push($datos, $info);
                $counter++;
            }
        }

        return array('data' => $datos);
    }


    public static function studentPayHistory($student, $year=null, $ciclo=null) {
        $database = DatabaseFactory::getFactory()->getConnection();

        // Setting year
        if ($year === null || $year === '') {
            $year = date('Y');
        }

        // Setting ciclo
        if ($ciclo === null || $ciclo === '') {
            $ciclo = self::getCiclo(date('m'));
        }

        // SQL: student history pay in given year and ciclo
        $history =  $database->prepare("SELECT * FROM students_pays 
                                        WHERE student_id = :student
                                          AND year       = :year
                                          AND ciclo      = :ciclo
                                        LIMIT 1;");
        $history->execute(array(':student' => $student,
                                ':year'    => $year,
                                ':ciclo'   => $ciclo));
        if ($history->rowCount() > 0) {
            return $history->fetch();
        }

        return null;
    }

    public static function payMonth($student, $month, $action, $year=null, $ciclo=null){
        $database = DatabaseFactory::getFactory()->getConnection();
        // Si no se especifica año y ciclo, se toman los actuales
        if ($year == null || $year == '') {
            $year = date('Y');
        }

        if ($ciclo == null || $ciclo == '') {
            $ciclo = self::getCiclo(date('m'));
        }

        // dump($student, $month, $action, $year, $ciclo);
        // exit();

        // Validar si el alumno esta en la tabla pagos de la lista actual
        $get_student =  $database->prepare("SELECT student_id 
                                            FROM students_pays 
                                            WHERE student_id = :student
                                              AND year  = :year;");
        $get_student->execute(array(':student' => $student,
                                    ':year'    => $year));

        $payed = false;
        if ($get_student->rowCount() > 0) {
            // Si esta en la tabla agregamos su pago en el mes correspondiente
            $set_pay =  $database->prepare("UPDATE students_pays 
                                            SET $month = :action
                                            WHERE student_id = :student
                                              AND year = :year;");
            $payed = $set_pay->execute(array(':student' => $student,
                                             ':action'  => $action,
                                             ':year'    => $year));
        } else {
            // Si no esta en la tabla lo agregamos como una nueva entrada
            $set_pay =  $database->prepare("INSERT INTO students_pays(student_id, $month, year)
                                                     VALUES(:student, :action, :year);");
            $set_pay->execute(array(':student' => $student,
                                    ':action'  => $action,
                                    ':year'    => $year));
            if ($set_pay->rowCount() > 0) {
                $payed = true;
            }
        }

        return array('success' => $payed);
    }

    public static function payMonthly($student, $month, $action, $comment, $year=null){
        $database = DatabaseFactory::getFactory()->getConnection();
        // Si no se especifica año y ciclo, se toman los actuales
        if ($year == null || $year == '') {
            $year = date('Y');
        }

        // Validar si el alumno esta en la tabla pagos de la lista actual
        $get_student =  $database->prepare("SELECT student_id 
                                            FROM students_pays 
                                            WHERE student_id = :student
                                              AND year  = :year;");
        $get_student->execute(array(':student' => $student,
                                    ':year'    => $year));

        $payed = false;
        if ($get_student->rowCount() > 0) {
            // Si esta en la tabla agregamos su pago en el mes correspondiente
            $set_pay =  $database->prepare("UPDATE students_pays 
                                            SET $month  = :action,
                                                comment = :comment
                                            WHERE student_id = :student
                                              AND year = :year;");
            $payed = $set_pay->execute(array(':student' => $student,
                                             ':action'  => $action,
                                             ':comment' => $comment,
                                             ':year'    => $year));
        } else {
            // Si no esta en la tabla lo agregamos como una nueva entrada
            $set_pay =  $database->prepare("INSERT INTO students_pays(student_id, $month, year, comment)
                                                     VALUES(:student, :action, :year, :comment);");
            $set_pay->execute(array(':student' => $student,
                                    ':action'  => $action,
                                    ':year'    => $year,
                                    ':comment' => $comment
                                ));
            if ($set_pay->rowCount() > 0) {
                $payed = true;
            }
        }

        return array('success' => $payed, 'message' => 'Pago de mensualidad actualizado con éxito');
    }

    public static function savePayMonthly($student, $month, $action, $comment, $families, $year=null){
        $database = DatabaseFactory::getFactory()->getConnection();
        // Si no se especifica año y ciclo, se toman los actuales
        if ($year == null || $year == '') {
            $year = date('Y');
        }

        // Validar si el alumno esta en la tabla pagos de la lista actual
        $get_student =  $database->prepare("SELECT student_id 
                                            FROM students_pays 
                                            WHERE student_id = :student
                                              AND year  = :year;");
        $get_student->execute(array(':student' => $student,
                                    ':year'    => $year));

        $payed = false;
        if ($get_student->rowCount() > 0) {
            // Si esta en la tabla agregamos su pago en el mes correspondiente
            $set_pay =  $database->prepare("UPDATE students_pays 
                                            SET $month  = :action,
                                                comment = :comment
                                            WHERE student_id = :student
                                              AND year = :year;");
            $payed = $set_pay->execute(array(':student' => $student,
                                             ':action'  => $action,
                                             ':comment' => $comment,
                                             ':year'    => $year));
        } else {
            // Si no esta en la tabla lo agregamos como una nueva entrada
            $set_pay =  $database->prepare("INSERT INTO students_pays(student_id, $month, year, comment)
                                                     VALUES(:student, :action, :year, :comment);");
            $set_pay->execute(array(':student' => $student,
                                    ':action'  => $action,
                                    ':year'    => $year,
                                    ':comment' => $comment
                                ));
            if ($set_pay->rowCount() > 0) {
                $payed = true;
            }
        }

        foreach ($families as $relative) {
            // Validar si el alumno esta en la tabla pagos de la lista actual
            $exists =  $database->prepare("SELECT student_id 
                                                FROM students_pays 
                                                WHERE student_id = :student
                                                  AND year  = :year;");
            $exists->execute(array(':student' => $relative,
                                    ':year'    => $year));

            if ($exists->rowCount() > 0) {
                // Si esta en la tabla agregamos su pago en el mes correspondiente
                $set_pay =  $database->prepare("UPDATE students_pays 
                                                SET $month  = :action,
                                                    comment = :comment
                                                WHERE student_id = :student
                                                  AND year = :year;");
                $payed = $set_pay->execute(array(':student' => $relative,
                                                 ':action'  => $action,
                                                 ':comment' => $comment,
                                                 ':year'    => $year));
            } else {
                // Si no esta en la tabla lo agregamos como una nueva entrada
                $set_pay =  $database->prepare("INSERT INTO students_pays(student_id, $month, year, comment)
                                                         VALUES(:student, :action, :year, :comment);");
                $set_pay->execute(array(':student' => $relative,
                                        ':action'  => $action,
                                        ':year'    => $year,
                                        ':comment' => $comment
                                    ));
            }
        }

        return array('success' => $payed, 'message' => 'Pago de mensualidad realizado con éxito');
    }

    public static function saveComment($student, $comment, $year=null, $ciclo=null){
        $database = DatabaseFactory::getFactory()->getConnection();
        // Si no se especifica año y ciclo, se toman los actuales
        if ($year == null || $year == '') {
            $year = date('Y');
        }

        if ($ciclo == null || $ciclo == '') {
            $ciclo = self::getCiclo(date('m'));
        }


        // Validar si el alumno esta en la tabla pagos de la lista actual
        $get_student =  $database->prepare("SELECT student_id 
                                            FROM students_pays 
                                            WHERE student_id = :student
                                              AND year  = :year;");
        $get_student->execute(array(':student' => $student,
                                    ':year'    => $year));

        $saved = false;
        if ($get_student->rowCount() > 0) {
            // Si esta en la tabla agregamos su pago en el mes correspondiente
            $set_comment =  $database->prepare("UPDATE students_pays 
                                                SET comment = :comment
                                                WHERE student_id = :student
                                                  AND year  = :year;");
            $saved = $set_comment->execute(array(':student' => $student,
                                                 ':comment'  => $comment,
                                                 ':year'     => $year));
        } else {
            // Si no esta en la tabla lo agregamos como una nueva entrada
            $set_comment =  $database->prepare("INSERT INTO students_pays(student_id, year, ciclo, comment)
                                                     VALUES(:student, :year, :ciclo, :comment);");
            $set_comment->execute(array(':student' => $student,
                                        ':comment'  => $comment,
                                        ':year'    => $year,
                                        ':ciclo'   => $ciclo));
            if ($set_comment->rowCount() > 0) {
                $saved = true;
            }
        }

        return array('success' => $saved);
    }




    public static function getPayYearList(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $get_year = $database->prepare("SELECT year FROM students_pays ORDER BY year ASC LIMIT 1");
        $get_year->execute();

        $years = [];
        $years[0] = new stdClass();
        $years[0]->year = (int)date('Y');
        if ($get_year->rowCount() > 0) {
            $year = $get_year->fetch();
            $diferencia = (int)date('Y') - (int)$year->year;
            for ($i = 1; $i <= $diferencia; $i++) {
                $years[$i] = new stdClass();
                $years[$i]->year = (int)date('Y') - $i;
            }
            return $years;
        }

        return $years;
    }


    public static function statusIcon($status) {

        $status_icon = '<i class="fa fa-check"></i>';
        switch ($status) {
            case '0': $status_icon = '<i class="fa fa-check"></i>'; break;
            case '1': $status_icon = '<i class="fa fa-check-circle"></i>'; break;
            case '2': $status_icon = '<i class="mdi-action-beca">B</i>'; break;
            case '3': $status_icon = '<i class="mdi-action-na">N/A</i>'; break;
        }
        return $status_icon;

    } //-- Fin de la funcion getMonth()

    public static function getCiclo($month) {
        if ($month < 8) {
            // Agosto, Septiembre, Octubre, Noviembre, Diciembre
            $ciclo = 'B';
        } else {
            // Enero, Febrero, Marzo, Abril, Mayo, Junio y Julio
            $ciclo = 'A';
        }
         return $ciclo;
    }
}
