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
                $grupo = '<a href="javascript:void(0)" 
                             class="link add_to_group badge badge-warning" 
                             data-student="'.$alumno->student_id.'"
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
}