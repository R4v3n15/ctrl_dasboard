<?php

class BecasModel
{
	public static function getScholars(){
		$database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT s.student_id, 
        			   s.id_tutor, 
        			   CONCAT_WS(' ', surname, lastname, name) as name, 
        			   s.genre, 
        			   s.cellphone, 
        			   s.avatar,
        			   sg.class_id,
        			   b.beca_id,
        			   b.sponsor_id
                FROM students as s, students_groups as sg, becas as b
                WHERE s.student_id = sg.student_id
                  AND s.student_id = b.student_id
                  AND b.status     = 1
                ORDER BY surname ASC";

        $query = $database->prepare($sql);
        $query->execute();

        $alumnos = array();
        if ($query->rowCount() > 0) {
        	$alumnos = $query->fetchAll();
	        foreach ($alumnos as $alumno) {
	        	$tutor = null;
	        	if ((int)$alumno->id_tutor !== 0) {
	        		$getTutor = $database->prepare("SELECT CONCAT_WS(' ', namet, surnamet) as name, 
	        												cellphone, 
	        												phone, 
	        												relationship, 
	        												phone_alt, 
	        												relationship_alt
	        										FROM tutors 
	        										WHERE id_tutor = :tutor 
	        										LIMIT 1;");
	        		$getTutor->execute(array(':tutor' => $alumno->id_tutor));
	        		if ($getTutor->rowCount() > 0) {
	        			$tutor = $getTutor->fetch();
	        		}
	        	}
	        	$alumno->tutor = $tutor;

	        	$clase   = null;
	        	$maestro = null;
	        	$dias    = null;
	        	$horario = null;
	        	if ($alumno->class_id !== null) {
	        		$getClase = $database->prepare("SELECT c.schedul_id, 
	        												c.teacher_id, 
	        												CONCAT_WS(' ', co.course, g.group_name) AS clase,
	        												h.hour_init, h.hour_end
	        										FROM classes as c, courses as co, groups as g, schedules as h
	        										WHERE c.class_id  = :clase
	        										  AND c.course_id = co.course_id
	        										  AND c.group_id  = g.group_id
	        										  AND c.schedul_id = h.schedul_id
	        										LIMIT 1;");
	        		$getClase->execute(array(':clase' => $alumno->class_id));
	        		if($getClase->rowCount() > 0){
	        			$result  = $getClase->fetch();
	        			$clase   = $result->clase;
	        			$horario =  date('g:i a', strtotime($result->hour_init)) . ' - ' . date('g:i a', strtotime($result->hour_end));

	        			if ($result->teacher_id !== null) {
	        				$getTeacher = $database->prepare("SELECT CONCAT_WS(' ', name, lastname) as name 
	        												  FROM users 
	        												  WHERE user_id = :teacher 
	        												  LIMIT 1;");
	        				$getTeacher->execute(array(':teacher' => $result->teacher_id));
	        				$getTeacher = $getTeacher->fetch();
	        				$maestro = $getTeacher->name;
	        			}

	        			$getDays = $database->prepare("SELECT d.day 
                                                       FROM days as d, schedul_days as sd 
                                                       WHERE sd.schedul_id = :schedul
                                                         AND sd.day_id     = d.day_id
                                                       ORDER BY d.day_id;");
                        $getDays->execute(array(':schedul' => $result->schedul_id));
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
	        		}
	        	}

	        	$padrino = null;
	        	$getSponsor  =  $database->prepare("SELECT CONCAT_WS(' ', s.sp_name, s.sp_surname) as sponsor
	        									  	FROM sponsors as s, becas as b
	        									  	WHERE b.student_id = :student
	        									  	  AND b.status     = 1
	        									  	  AND b.sponsor_id = s.sponsor_id
	        									  	LIMIT 1;");
	        	$getSponsor->execute([':student' => $alumno->student_id]);

	        	if ($getSponsor->rowCount() > 0) {
	        		$padrino = $getSponsor->fetch()->sponsor;
	        	}

	        	$alumno->clase   = $clase;
	        	$alumno->maestro = $maestro;
	        	$alumno->dias    = $dias;
	        	$alumno->horario = $horario;
	        	$alumno->padrino = $padrino;
        	}
        }
        // dump($alumnos);
        // exit();
        return $alumnos;
	}

	public static function saveScholar($student, $date_register, $sponsor){
		$database = DatabaseFactory::getFactory()->getConnection();
		// Validate if exists sponsor
		$sponsor = $sponsor == '' ? null : $sponsor;

		// Validate if exists a pre register
		$query = $database->prepare("SELECT * FROM becas WHERE student_id = :student AND status = 2;");
		$query->execute(array(':student' => $student));

		if ($query->rowCount() > 0) {
			$becario = $database->prepare("UPDATE becas SET status = 1 WHERE student_id = :student AND status = 2;");
			return $becario->execute([':student' => $student]);
		} else {
			$becario = $database->prepare("INSERT INTO becas(student_id, sponsor_id, status, granted_at) 
													  VALUES(:student, :sponsor, 1, :date_register);");
			$becario->execute([':student' => $student, ':date_register' => $date_register, ':sponsor' => $sponsor]);

			return $becario->rowCount() > 0;
		}
	}

	public static function removeScholar($student){
		$database = DatabaseFactory::getFactory()->getConnection();

		$removed_at = H::getTime('Y-m-d');

		$becario = $database->prepare("UPDATE becas 
									   SET status = 0, removed_at = :date_removed 
									   WHERE student_id = :student AND status = 1;");
		return $becario->execute([':student' => $student, ':date_removed' => $removed_at]);
	}

	public static function saveApplicant($student, $date_request){
		$database = DatabaseFactory::getFactory()->getConnection();
		// Validate if exists sponsor
		$sponsor =  null;

		// Deactivate if exist pre register active
		$query = $database->prepare("SELECT * FROM becas WHERE student_id = :student AND status != 0;");
		$query->execute(array(':student' => $student));

		if ($query->rowCount() > 0) {
			$becario = $database->prepare("UPDATE becas SET status = 0 WHERE student_id = :student;");
			return $becario->execute([':student' => $student]);
		} 
		$becario = $database->prepare("INSERT INTO becas(student_id, sponsor_id, status, applicant_at) 
												  VALUES(:student, :sponsor, 2, :date_register);");
		$becario->execute([':student' => $student, ':date_register' => $date_request, ':sponsor' => $sponsor]);

		return $becario->rowCount() > 0;
		
	}

	public static function removeApplicant($student){
		$database = DatabaseFactory::getFactory()->getConnection();

		$becario = $database->prepare("UPDATE becas SET status = 0 WHERE student_id = :student AND status = 2;");
		return $becario->execute([':student' => $student]);
	}
}
