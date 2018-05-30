<?php

class AlumnoModel
{
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

            H::p($alumno);
            exit();
            return $alumno;
        }
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
                $_sql = $database->prepare("SELECT c.class_id, g.course_id,
                                                    g.group_id, cu.course_name, g.group_name
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

            // H::p($details);
            // exit();

            return $details;
        }

        return null;
    }

}
