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
                                               g.convenio, sd.studies, sd.lastgrade, sd.workplace as school
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
                                               g.convenio, sd.studies, sd.lastgrade, sd.workplace as school
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
                                               g.convenio, sd.studies, sd.lastgrade, sd.workplace as school
                                        FROM students as s, students_groups as g, students_details as sd, classes as c
                                        WHERE s.status = 1
                                          AND s.deleted  = 0
                                          AND s.student_id = g.student_id
                                          AND g.class_id   = c.class_id
                                          AND s.student_id = sd.student_id
                                          AND c.course_id  = :course 
                                        ORDER BY s.surname ASC, s.lastname ASC;");
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



    public static function filter($category, $param){
        switch ($category) {
            case 'alumno':
                self::filterStudent($param, null, null);
                break;
            case 'grupo':
                // code...
                break;
            case 'tutor':
                // code...
                break;
            case 'edad':
                // code...
                break;
            case 'escuela':
                // code...
                break;
            case 'grado':
                // code...
                break;
            default:
                return array('message' => 'Nothing found');
                break;
        }
    }

    // Prevencion de Duplicidad
    public static function filterStudent($name, $surname=null, $lastname=null){

        $database = DatabaseFactory::getFactory()->getConnection();
        $name     = '%'.trim($name).'%';
        $surname  = $surname  !== null && $surname  !== '' ? '%'.trim($surname).'%' : null;
        $lastname = $lastname !== null && $lastname !== '' ? '%'.trim($lastname).'%' : null;

        if ($name !== '') {
            if ($surname === null && $lastname === null ) {
                // return array('name' => $name, 'surname' => $surname, 'lastname' => $lastname);
                $search =  $database->prepare("SELECT name, surname, lastname
                                                FROM students
                                                WHERE name LIKE :name;");
                $search->execute(array( ':name' => $name));
            } else if($surname !== null && $lastname === null){
                $search =  $database->prepare("SELECT student_id
                                                FROM students
                                                WHERE name LIKE :name
                                                  AND surname LIKE :surname;");
                $search->execute(array( ':name'     => $name, 
                                        ':surname'  => $surname));
            } else {
                $search =  $database->prepare("SELECT student_id
                                                FROM students
                                                WHERE name LIKE :name
                                                  AND surname LIKE :surname
                                                  AND lastname LIKE :lastname;");
                $search->execute(array( ':name'     => $name, 
                                        ':surname'  => $surname, 
                                        ':lastname' => $lastname));
            }
        } else {
            if($surname !== null && $lastname === null){
                $search =  $database->prepare("SELECT student_id
                                                FROM students
                                                WHERE name LIKE :name
                                                  AND surname LIKE :surname;");
                $search->execute(array( ':name'     => $name, 
                                        ':surname'  => $surname));
            } else if($surname === null && $lastname !== null) {
                $search =  $database->prepare("SELECT student_id
                                                FROM students
                                                WHERE name LIKE :name
                                                  AND surname LIKE :surname
                                                  AND lastname LIKE :lastname;");
                $search->execute(array( ':name'     => $name, 
                                        ':surname'  => $surname, 
                                        ':lastname' => $lastname));
            } else {
                $search =  $database->prepare("SELECT student_id
                                                FROM students
                                                WHERE name LIKE :name
                                                  AND surname LIKE :surname
                                                  AND lastname LIKE :lastname;");
                $search->execute(array( ':name'     => $name, 
                                        ':surname'  => $surname, 
                                        ':lastname' => $lastname));
            }
        }

        return $search->fetchAll();
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




    public static function prospectSepStudents() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, CONCAT_WS(' ',s.name, s.surname, s.lastname)  as name, 
                                               s.age, s.genre, s.avatar, s.cellphone, g.class_id,
                                               sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd
                                        WHERE s.status  = 1
                                          AND s.deleted = 0
                                          AND s.age     > 14
                                          AND s.student_id = g.student_id
                                          AND s.student_id = sd.student_id;");
        $students->execute();
        if ($students->rowCount() > 0) {
            return $students->fetchAll();
        }

        return null;
    }



    

    public static function createBackupDatabase(){
        $host = Config::get('DB_HOST'); //Host del Servidor MySQL
        $name = Config::get('DB_NAME'); //Nombre de la Base de datos
        $user = Config::get('DB_USER'); //Usuario de MySQL
        $pass = Config::get('DB_PASS'); //Password de Usuario MySQL
        $bk_path = Config::get('PATH_BACKUPS'); //Carpeta destino del Backup

        $backup_file = $bk_path . $name . '_' .date("Ymd-His") . ".sql";
        echo "<h3>Backing up database to `<code>{$backup_file}</code>`</h3>";
        $cmd = "C:\\xampp\mysql\bin\mysqldump.exe -h {$host} -u {$user} -p{$pass} {$name} > $backup_file";
         
        exec($cmd,$output);
        var_dump($output);
    }


    ////////////////////////////////////////////////////////////
    //  =   =   =   =   =   = Alumnos De Baja   =   =   =   = //
    ////////////////////////////////////////////////////////////

    public static function allStudentsUnsuscribe() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $students = $database->prepare("SELECT s.student_id, s.id_tutor, CONCAT_WS(' ',s.name, s.surname, s.lastname) as name, 
                                               s.age, s.genre, s.avatar, g.class_id,
                                               g.convenio, sd.studies, sd.lastgrade
                                        FROM students as s, students_groups as g, students_details as sd
                                        WHERE s.status     = 0
                                          AND s.deleted    = 0
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









    public static function cleanDatabase(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $database->beginTransaction();

        try{
            $cleanAddress = $database->prepare("TRUNCATE TABLE address;");
            $cleanAddress->execute();

            $cleanBecas = $database->prepare("TRUNCATE TABLE becas;");
            $cleanBecas->execute();

            $cleanStudents = $database->prepare("TRUNCATE TABLE students;");
            $cleanStudents->execute();

            $cleanDetails = $database->prepare("TRUNCATE TABLE students_details;");
            $cleanDetails->execute();

            $cleanGroups = $database->prepare("TRUNCATE TABLE students_groups;");
            $cleanGroups->execute();

            $cleanPays = $database->prepare("TRUNCATE TABLE students_pays;");
            $cleanPays->execute();

            $cleanTutors = $database->prepare("TRUNCATE TABLE tutors;");
            $cleanTutors->execute();

        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; Error al tratar de limpiar Base de Datos!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Base de Datos limpiada correctamente!!');
        }
    }


    public static function feedDatabase(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $commit   = true;
        $database->beginTransaction();

        try{
            
            if ($feedAddress->rowCount() < 1) {
                $commit = false;
            }
          
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array('success' => false, 'message' => '&#x2718; Error al tratar de actualizar Base de Datos!');
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; Base de Datos actualizada correctamente!!');
        }
    }



    public static function makeBackupDatabase(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $dbname = Config::get('DB_NAME');
        $path   = Config::get('PATH_BACKUPS'); //Carpeta destino del Backup
        $backup = $path . $dbname . '_' .date("Ymdhms") . ".sql";
        $backup = str_replace('/', '\\', $backup);
        /* Determina si la tabla será vaciada (si existe) cuando restauremos la tabla. */ 
        $drop = false;
        $tablas = false; //tablas de la bd
        // Tipo de compresion: "gz", "bz2", o false (sin comprimir)
        $compresion = false;

        $tables = $database->prepare("SHOW TABLES;");
        $tables->execute();

        if ($tables->rowCount() > 0) {
            $tables = $tables->fetchAll(PDO::FETCH_COLUMN);
            
            $info['fecha'] = date('d-m-Y');
            $info['hora']  = date('h:m:s A');
            $info['mysqlver'] = $database->getAttribute(PDO::ATTR_SERVER_VERSION);
            $info['phpver'] = phpversion();

            ob_start();
            print_r($tables);
            $representacion = ob_get_contents();
            ob_end_clean();

            preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
            $info['tablas'] = implode("; ", $matches[1]);

            // Text Info
            $dump  = "-- SQL DUMP \n";
            $dump .= "-- Servidor: 127.0.0.1 \n";
            $dump .= "-- Tiempo de generación: {$info['fecha']} a las {$info['hora']} \n";
            $dump .= "-- Versión del servidor: {$info['mysqlver']} \n";
            $dump .= "-- Versión de PHP: {$info['phpver']} \n";
            $dump .= "-- \n\n";
            $dump .= 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";' ."\n";
            $dump .= 'SET AUTOCOMMIT = 0;' . "\n";
            $dump .= 'START TRANSACTION;' . "\n";
            $dump .= 'SET time_zone = "+00:00";' . "\n\n";
            $dump .= "-- Base de Datos `$dbname` \n";
            $dump .= "-- \n";
            $dump .= "CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER ";
            $dump .= "SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n USE `$dbname`; \n\n";
            $dump .= "-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";

            // Crear las consultas necesarias para el script de respaldo
            foreach ($tables as $tabla) {
                $drop_table   = "";
                $create_table = "";
                $insert_into  = "";

                /* Se halla query que será capaz vaciar la tabla. */
                $drop_table = "DROP TABLE IF EXISTS `$tabla`;";


                /* Se halla query que será capaz de recrear la estructura de la tabla. */
                $consulta = $database->prepare("SHOW CREATE TABLE $tabla;");
                $consulta->execute();
                $respuesta = $consulta->fetchAll(PDO::FETCH_COLUMN, 1);

                foreach ($respuesta as $value) {
                    $create_table = $value.";";
                }

                /* Se halla el query que será capaz de insertar los datos. */
                $query = $database->prepare("SELECT * FROM $tabla;");
                $query->execute();

                if ($query->rowCount() > 0) {
                    $response = $query->fetchAll(PDO::FETCH_ASSOC);

                    $cont = 0;
                    $data = null;
                    foreach ($response as $fila) {
                        $columnas = array_keys($fila);

                        if ($cont === 0) {
                            $insert_into .= "INSERT INTO `$tabla` (`".implode("`, `", $columnas)."`) VALUES\n";
                        }
                        
                        $data = "(";
                        $i = 0;
                        foreach ($columnas as $columna) {
                            $data .= $fila[$columna] == NULL ? 'NULL' : "`".$fila[$columna]."`";

                            $data .= $i+1 < count($columnas) ? ', ' : "";

                            $i++;
                        }

                        $data .= $cont+1 < count($response) ?  ")," : ");";
                        
                        $insert_into .= $data."\n";
                        $cont++;
                        
                    } // end foreach->response

                    unset($data);

                    $dump .= "-- \n";
                    $dump .= "-- Eliminar tabla `$tabla` existente\n";
                    $dump .= "-- \n";
                    $dump .= $drop_table;
                    $dump .= "\n\n";

                    $dump .= "-- \n";
                    $dump .= "-- Estructura de la tabla `$tabla` \n";
                    $dump .= "-- \n";
                    $dump .= $create_table;
                    $dump .= "\n\n";

                    $dump .= "-- \n";
                    $dump .= "-- Volcado de datos para la tabla `$tabla` \n";
                    $dump .= "-- \n";
                    $dump .= $insert_into;
                    $dump .= "\n\n";

                    $dump .= "-- \n";
                    $dump .= "-- =  =  =  =  End Backup Script  =  =  =  = \n";
                    $dump .= "-- \n";

                }                    
            } // end foreach->tables

            
            $stored = file_put_contents($backup, $dump);

            if ($stored !== false && file_exists($backup)) {
                return array('success' => true, 'message' => 'Base de datos del sistema guardada correctamente en: '.$backup);
            } else {
                return array('success' => false, 'message' => 'Error! No se creo respaldo de la BD, informe al encargado de Sistemas.');
            }
        } // end if->tables
    }

    // TODO: Show sql files availables to restore
    public static function restoreDatabase(){
        $host = Config::get('DB_HOST'); //Host del Servidor MySQL
        $name = Config::get('DB_NAME'); //Nombre de la Base de datos
        $user = Config::get('DB_USER'); //Usuario de MySQL
        $pass = Config::get('DB_PASS'); //Password de Usuario MySQL
        $path = Config::get('PATH_BACKUPS'); //Carpeta destino del Backup

        $files = scandir($path);

        $restore = null;
        $created_at = 0;
        foreach ($files as $file) {
            if ((preg_match('/.sql/',$file)) ) {
                $root = $path . pathinfo($file)['basename'];

                if (filemtime($root) > $created_at) {
                    $restore = $root;
                    $created_at = filemtime($root);
                }
            }
        }
        // var_dump($restore);
        // exit();

        $cmd = "C:\\xampp\mysql\bin\mysql.exe -h {$host} -u {$user} -p{$pass} {$name} < $restore";
         
        exec($cmd,$output,$worked);

        if ((int)$worked === 0) {
            return array('success' => true, 'message' => 'Base de datos del sistema restaurado correctamente.');
        } else {
            return array('success' => false, 'message' => 'Error al tratar de restaurar la base de datos, notifique al encargado de Sistemas.');
        }
    }

}
