<?php

class GeneralModel
{
    public static function getStudentDetail($student){
        $database = DatabaseFactory::getFactory()->getConnection();

        $_sql = $database->prepare("SELECT s.student_id, s.id_tutor, CONCAT_WS(' ', s.name, s.surname, s.lastname) as name, 
                                           sg.class_id
                                    FROM students as s, students_groups as sg
                                    WHERE s.student_id = :student
                                      AND s.student_id = sg.student_id 
                                    LIMIT 1;");
        $_sql->execute(array(':student' => $student));

        $alumno = null;
        if ($_sql->rowCount() > 0) {
            $alumno = $_sql->fetch();

            $clase         = null;
            $c_normal      = null;
            $c_promocional = null;
            $c_inscripcion = null;
            $fecha         = null;
            $horas         = null;
            $dias          = null;
            if ($alumno->class_id !== null) {
                $getClass = $database->prepare("SELECT co.course, g.group_name, c.schedul_id, 
                                                       c.costo_normal, c.costo_promocional, c.costo_inscripcion,
                                                       s.date_init, CONCAT_WS(' - ', s.hour_init, s.hour_end) as horas 
                                                FROM classes as c, courses as co, groups as g, schedules as s
                                                WHERE c.class_id   = :clase
                                                  AND c.course_id  = co.course_id
                                                  AND c.group_id   = g.group_id
                                                  AND c.schedul_id = s.schedul_id
                                                LIMIT 1;");
                $getClass->execute(array(':clase' => $alumno->class_id));

                if ($getClass->rowCount() > 0) {
                    $result = $getClass->fetch();
                    $c_normal      = $result->costo_normal;
                    $c_promocional = $result->costo_promocional;
                    $c_inscripcion = $result->costo_inscripcion;
                    $clase         = ucwords(strtolower($result->course)) . ' ' . ucwords(strtolower($result->group_name));
                    $fecha         = $result->date_init;
                    $horas         = $result->horas;


                    // Obtener Dias de la clase
                    $getDays = $database->prepare("SELECT d.day     
                                                    FROM schedul_days as sd, days as d
                                                    WHERE sd.schedul_id = :schedul
                                                      AND sd.day_id     = d.day_id;");
                    $getDays->execute(array(':schedul' => $result->schedul_id));

                    if ($getDays->rowCount() > 0) {
                        $dias = $getDays->fetchAll();
                    }
                }
            }

            $alumno->c_normal      = $c_normal;
            $alumno->c_promocional = $c_promocional;
            $alumno->c_inscripcion = $c_inscripcion;
            $alumno->clase         = $clase;
            $alumno->fecha         = $fecha;
            $alumno->horas         = $horas;
            $alumno->dias          = $dias;

            $tutor = null;
            if ($alumno->id_tutor !== null) {
                $getTutor = $database->prepare("SELECT CONCAT_WS(' ', namet, surnamet, lastnamet) as name
                                                FROM tutors
                                                WHERE id_tutor = :tutor
                                                LIMIT 1;");
                $getTutor->execute(array(':tutor' => $alumno->id_tutor));

                if ($getClass->rowCount() > 0) {
                    $result = $getTutor->fetch();
                    $tutor  = $result->name;
                }
            }

            $alumno->tutor = $tutor;
        }
        // H::p($alumno);
        return $alumno;
    }


    ///////////////////////////////////////////////////
    //  =  =  =  =  = Alumnos Activos  =  =  =  =  = //
    ///////////////////////////////////////////////////
    public static function allStudents() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, s.name, s.surname,
                                               s.lastname, s.age, s.genre, s.avatar, g.class_id,
                                               g.convenio, sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd
                                        WHERE s.status = 1
                                          AND s.deleted  = 0
                                          AND s.student_id = g.student_id
                                          AND s.student_id = sd.student_id;");
        $students->execute();
        if ($students->rowCount() > 0) {
            return $students->fetchAll();
        }

        return null;
    }

    public static function standbyStudents() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, s.name, s.surname,
                                               s.lastname, s.age, s.genre, s.avatar, g.class_id,
                                               g.convenio, sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd
                                        WHERE s.status = 2
                                          AND s.deleted  = 0
                                          AND s.student_id = g.student_id
                                          AND s.student_id = sd.student_id;");
        $students->execute();
        if ($students->rowCount() > 0) {
            return $students->fetchAll();
        }

        return null;
    }

    public static function studentsByCourse($course){
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, s.name, s.surname,
                                               s.lastname, s.age, s.genre, s.avatar, g.class_id,
                                               g.convenio, sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd, classes as c
                                        WHERE s.status = 1
                                          AND s.deleted  = 0
                                          AND s.student_id = g.student_id
                                          AND g.class_id   = c.class_id
                                          AND s.student_id = sd.student_id
                                          AND c.course_id  = :course 
                                        ORDER BY s.surname ASC;");
        $students->execute(array(':course' => $course));
        if ($students->rowCount() > 0) {
            return $students->fetchAll();
        }

        return null;
    }

    // Counter
    public static function countAll() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id 
                                        FROM students as s, students_groups as g
                                        WHERE s.status = 1 
                                          AND s.deleted = 0
                                          AND s.student_id = g.student_id;");
        $students->execute();

        return $students->rowCount();
    }

    public static function countStandby() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT student_id FROM students WHERE status = 2 AND deleted = 0;");
        $students->execute();

        return $students->rowCount();
    }

    public static function countByCourse($course){
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, c.course_id
                                        FROM students as s, students_groups as g, classes as c
                                        WHERE s.status = 1
                                          AND s.deleted  = 0
                                          AND s.student_id = g.student_id
                                          AND g.class_id   = c.class_id
                                          AND c.course_id  = :course;");
        $students->execute(array(':course' => $course));

        return $students->rowCount();
    }





    // Prevencion de Duplicidad
    public static function existStudent($name, $surname, $lastname){
        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = '%'.trim($name).'%';
        $surname  = '%'.trim($surname).'%';
        $lastname = '%'.trim($lastname).'%';

        $search =  $database->prepare("SELECT student_id
                                        FROM students
                                        WHERE name LIKE :name
                                          AND surname LIKE :surname
                                          AND lastname LIKE :lastname;");
        $search->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));

        return $search->fetchAll();
    }

    public static function existTutor($name, $surname, $lastname=null){
        $database = DatabaseFactory::getFactory()->getConnection();
    }

    public static function existTeacher($name, $surname){
        $database = DatabaseFactory::getFactory()->getConnection();
    }

    public static function existSponsor($name, $surname){
        $database = DatabaseFactory::getFactory()->getConnection();
        $check_name = '%'.$name.'%';
        $check_surname = '%'.$surname.'%';
        $verify = $database->prepare("SELECT * FROM sponsors 
                                      WHERE sp_name LIKE :name 
                                        AND sp_surname LIKE :surname;");
        $verify->execute(array(':name' => $check_name, ':surname' => $check_surname));

        if ($verify->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public static function existClass($course, $group, $ciclo){
        $database = DatabaseFactory::getFactory()->getConnection();
    }

    public static function existCourse($course){
        $database = DatabaseFactory::getFactory()->getConnection();
    }

    public static function existGroup($group){
        $database = DatabaseFactory::getFactory()->getConnection();
    }

    

    public static function createBackupDatabase(){

        $db_host = Config::get('DB_HOST'); //Host del Servidor MySQL
        $db_name = Config::get('DB_NAME'); //Nombre de la Base de datos
        $db_user = Config::get('DB_USER'); //Usuario de MySQL
        $db_pass = Config::get('DB_PASS'); //Password de Usuario MySQL
        $bk_path = Config::get('PATH_BACKUPS'); //Carpeta destino del Backup

        $backup_file = $bk_path . $db_name . '_' .date("Ymd-His") . ".sql";
        $command = "mysqldump --opt -h $db_host  -u $db_user -p$db_pass $db_name > $backup_file";
         
        system($command,$output);
        echo $db_host.'<br>';
        echo $db_name.'<br>';
        echo $db_user.'<br>';
        echo $db_pass.'<br>';
        echo $output;
    }


    ////////////////////////////////////////////////////////////
    //  =   =   =   =   =   = Alumnos De Baja   =   =   =   = //
    ////////////////////////////////////////////////////////////

    public static function allStudentsDown() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, CONCAT_WS(' ',s.name, s.surname, s.lastname) as name, 
                                               s.age, s.genre, s.avatar, g.class_id,
                                               g.convenio, sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd
                                        WHERE s.status = 2
                                          AND s.deleted  = 0
                                          AND s.student_id = g.student_id
                                          AND s.student_id = sd.student_id;");
        $students->execute();
        if ($students->rowCount() > 0) {
            return $students->fetchAll();
        }

        return null;
    }

    public static function allStudentsDeleted() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, CONCAT_WS(' ',s.name, s.surname, s.lastname) as name, 
                                               s.age, s.genre, s.avatar, g.class_id,
                                               g.convenio, sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd
                                        WHERE s.deleted  = 1
                                          AND s.student_id = g.student_id
                                          AND s.student_id = sd.student_id;");
        $students->execute();
        if ($students->rowCount() > 0) {
            return $students->fetchAll();
        }

        return null;
    }

}
