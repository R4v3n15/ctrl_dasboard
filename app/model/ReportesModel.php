<?php

class ReportesModel
{
	public static function StudentsTable($course){
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
                $grupo = '<span class="badge badge-secondary">Sin Grupo</span>';

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
                        $grupo = '<span class="badge badge-info">'.$nombre_curso.' '.$clase->group_name.'</span>';
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

                $info = array(
                    'count'      => $counter,
                    'surname'    => $alumno->surname.' '.$alumno->lastname,
                    'name'       => $alumno->name,
                    'studies'    => $alumno->studies.' '.$alumno->lastgrade,
                    'school'     => $alumno->school,
                    'age'        => $alumno->age,
                    'group_name' => $grupo,
                    'tutor_name' => $nombre_tutor
                );

                array_push($datos, $info);
                $counter++;
            }
        }

        return array('data' => $datos);   
    }


    public static function register(){
        $cursos = self::Courses();

        $total = 0;
        foreach ($cursos as $curso) {
            $curso->name    = ucwords(strtolower($curso->course));
            $grupos = self::groupsByCourse($curso->course_id);
            $curso->grupos  = $grupos['grupos'];
            $total         += $grupos['totalAlumnos'];
        }

        return array('cursos' => $cursos, 'totalAlumnos' => $total);
    }

    public static function Courses(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT course_id, course FROM courses");
        $query->execute();    

        return $query->fetchAll();
    }
    
    public static function groupsByCourse($course){
        $database = DatabaseFactory::getFactory()->getConnection();

        $_sql = $database->prepare('SELECT c.class_id, g.* 
                                    FROM classes as c, groups as g
                                    WHERE c.course_id = :course 
                                      AND c.status    = 1
                                      AND c.group_id  = g.group_id;');
        $_sql->execute(array(':course' => $course));

        $grupos = $_sql->fetchAll();
        $total  = 0;
        foreach ($grupos as $grupo) {
            $grupo->alumnos = self::countStudenByGroup($grupo->class_id);
            $total += $grupo->alumnos;
        }

        return array('grupos' => $grupos, 'totalAlumnos' => $total);
    }

    public static function countStudenByGroup($clase){
        $database = DatabaseFactory::getFactory()->getConnection();

        $students = $database->prepare("SELECT s.student_id
                                        FROM students as s, students_groups as g
                                        WHERE s.status     = 1
                                          AND s.deleted    = 0
                                          AND s.student_id = g.student_id
                                          AND g.class_id   = :clase;");
        $students->execute(array(':clase' => $clase));

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

    public static function countStudentsWithoutGroup(){
        $database = DatabaseFactory::getFactory()->getConnection();
       
        $_sql = $database->prepare('SELECT class_id FROM classes WHERE status = 3;');
        $_sql->execute();

        $total = 0;
        foreach ($_sql->fetchAll() as $clase) {
            $query = $database->prepare("SELECT s.student_id
                                        FROM students as s, students_groups as g
                                        WHERE s.status     = 1
                                          AND s.deleted    = 0
                                          AND s.student_id = g.student_id
                                          AND g.class_id   = :clase;");
            $query->execute(array(':clase' => $clase->class_id));

            $total += $query->rowCount();
        }

        return $total;
    }
}