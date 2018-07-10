<?php

class CleanModel
{
	public static function deleteStudent($student){
		$database = DatabaseFactory::getFactory()->getConnection();

		$select = $database->prepare("SELECT student_id, id_tutor 
									  FROM students 
									  WHERE student_id = :student
									  LIMIT 1;");
		$select->execute(array(':student' => $student));
		$alumno = $select->fetch();

		$commit = true;
        $database->beginTransaction();
        try{
        	$delete = $database->prepare("DELETE FROM students WHERE student_id = :student;");
        	$delete->execute(array(':student' => $student));

            if ($delete->rowCount() > 0) {
            	$removeD = $database->prepare("DELETE FROM students_details WHERE student_id = :student;");
            	$removeD->execute(array(':student' => $student));
            	$removeE = $database->prepare("DELETE FROM students_evaluations WHERE student_id = :student;");
            	$removeE->execute(array(':student' => $student));
            	$removeG = $database->prepare("DELETE FROM students_groups WHERE student_id = :student;");
            	$removeG->execute(array(':student' => $student));
            	$removeP = $database->prepare("DELETE FROM students_pays WHERE student_id = :student;");
            	$removeP->execute(array(':student' => $student));
            	$removeS = $database->prepare("DELETE FROM students_sep WHERE student_id = :student;");
            	$removeS->execute(array(':student' => $student));

            	if ((int)$alumno->id_tutor !== 0) {
                    $_sql = $database->prepare("SELECT student_id, id_tutor 
                                                  FROM students 
                                                  WHERE id_tutor = :tutor
                                                   AND student_id != :student
                                                  LIMIT 1;");
                    $_sql->execute(array(':tutor' => $alumno->id_tutor, ':student' => $student));

                    if ($_sql->rowCount() < 1) {
                		$clean = $database->prepare("DELETE FROM tutors WHERE id_tutor = :tutor;");
                		$clean->execute(array(':tutor' => $alumno->id_tutor));

                		if ($clean->rowCount() > 0) {
                			$removeSt = $database->prepare("DELETE FROM address WHERE user_id = :tutor AND user_type = 1;");
    	            		$removeSt->execute(array(':tutor' => $alumno->id_tutor));
                		} else {
                			$commit = false;
                		}
                    }
            	} else {
            		$removeSt = $database->prepare("DELETE FROM address WHERE user_id = :student AND user_type = 2;");
            		$removeSt->execute(array(':student' => $student));
            	}
            } else {
            	$commit = false;
            }           
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();

            return array('deleted' => false);
        } else {
            $database->commit();
            return array('deleted' => true);
        }
	}

	public static function deleteTutor($tutor){
		$database = DatabaseFactory::getFactory()->getConnection();

		$select = $database->prepare("SELECT student_id, id_tutor 
									  FROM students 
									  WHERE id_tutor = :tutor
									  LIMIT 1;");
		$select->execute(array(':tutor' => $tutor));

		if ($select->rowCount() === 0) {
			$clean = $database->prepare("DELETE FROM tutors WHERE id_tutor = :tutor;");
            $clean->execute(array(':tutor' => $tutor));

            if ($clean->rowCount() > 0) {
            	$removeSt = $database->prepare("DELETE FROM address WHERE user_id = :tutor AND user_type = 1;");
	            $removeSt->execute(array(':tutor' => $alumno->id_tutor));
	            return array('deleted' => true, 'message' => 'Tutor eliminado correctamente');
            } else {
            	return array('deleted' => false, 'message' => 'Error al tratar de aliminar tutor');
            }
		} else {
			return array('deleted' => false, 'message' => 'No se puede eliminar al tutor, tiene mas de un alumno inscrito');
		}
	}

	public static function deleteSponsor($sponsor){
		$database = DatabaseFactory::getFactory()->getConnection();

		$delete = $database->prepare("DELETE FROM sponsors WHERE sponsor_id = :sponsor;");
		$delete->execute(array(':sponsor' => $sponsor));

		if ($delete->rowCount() > 0) {
			return array('deleted' => true, 'message' => 'Padrino eliminado');
		}

		return array('deleted' => false, 'message' => 'No se puede eliminar al padrino');
	}

}
