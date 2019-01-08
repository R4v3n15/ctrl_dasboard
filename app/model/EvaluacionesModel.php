<?php

class EvaluacionesModel
{
    public static function getStudentName($student){
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT CONCAT_WS(' ', name, surname, lastname) as name 
                                     FROM students 
                                     WHERE student_id = :student
                                     LIMIT 1;");
        $query->execute(array(':student' => $student));

        if ($query->rowCount() > 0) {
            return $query->fetch()->name;
        }

        return null;
    }

    public static function getStudentData($student) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT course_id, course 
                FROM courses";
        $query = $database->prepare($sql);
        $query->execute();    

        return $query->fetchAll();
    }

    public static function saveEvaluation($month_from, $mont_to, $ciclo, $teacher, $student, $group, $subject, $read_achieve, $write_achieve, $speak_achieve, $listen_achieve, $read_effort, $write_effort, $speak_effort, $listen_effort, $participation, $teamwork, $homeworks, $comments, $tutor, $date_eval){

        $sql = "INSERT INTO students_evaluations(student_id, grade, period, bimestry, 
                                                 subjects, date_evaluation, read_achieve, 
                                                 write_achieve, speak_achieve, listen_achieve, 
                                                 read_effort, write_effort, speak_effort, 
                                                 listen_effort, participation_effort, teamwork_effort, 
                                                 timming_effort, annotations, teacher, tutor) 
                                        VALUES(:student, :grade, :period, :bimestry, :subject, :date_eval,
                                               :read_achieve, :write_achieve, :speak_achieve, :listen_achieve,
                                               :read_effort, :write_effort, :speak_effort, :listen_effort,
                                               :participation, :teamwork, :homeworks, :comments, :teacher, :tutor);";
        $save = $database->prepare($sql);
        $save->execute(array(
                            ':student'         => $student,
                            ':grade'           => $group,
                            ':period'          => $ciclo,
                            ':bimestry'        => $month_from .'-'.$mont_to,
                            ':subject'         => $subject,
                            ':date_eval'       => $date_eval,
                            ':read_achieve'    => $read_achieve,
                            ':write_achieve'   => $write_achieve,
                            ':speak_achieve'   => $speak_achieve,
                            ':listen_achieve'  => $listen_achieve,
                            ':read_effort'     => $read_effort,
                            ':write_effort'    => $write_effort,
                            ':speak_effort'    => $speak_effort,
                            ':listen_efforte'  => $listen_effort,
                            ':participation'   => $participation,
                            ':teamwork'        => $teamwork,
                            ':homeworks'       => $homeworks,
                            ':comments'        => $comments,
                            ':teacher'         => $teacher,
                            ':tutor'           => $tutor
        ));

    }
}
