<?php

class MaestroModel
{

    public static function getTeachers() {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, name, lastname, user_email, user_phone, user_avatar
                FROM users
                WHERE user_type = 3
                  AND user_deleted = 0;";
        $query = $database->prepare($sql);
        $query->execute();    

        return $query->fetchAll();
    }

    public static function teachersTable($page){
        H::getLibrary('paginadorLib');
        $paginator = new \Paginador();
        $page      = (int)$page;
        $rows      = 10;

        $teachers  = self::getTeachers();
        
        if (count($teachers) > 0) {
            $maestros   = $paginator->paginar($teachers, $page, $rows);
            $counter    = $page > 0 ? (($page*$filas)-$filas) + 1 : 1;
            $paginacion = $paginator->getView('pagination_ajax', 'teachers');

            // Tabla de maestros
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-hover table-striped">';
                echo '<thead>';
                   echo '<tr class="info">';
                      echo '<th>ID</th>';
                      echo '<th>Foto</th>';
                      echo '<th>Nombre</th>';
                      echo '<th>Apellido</th>';
                      echo '<th>Correo Electronico</th>';
                      echo '<th>Teléfono</th>';
                      echo '<th class="text-center">Opciones</th>';
                   echo '</tr>';
                echo '</thead>';
                echo '<tbody>'; 
                    foreach ($maestros as $index => $maestro) {
                        $img = $maestro->user_avatar === NULL ? 'male' : $maestro->user_avatar;
                        $file = Config::get('URL').Config::get('PATH_AVATAR_USER').$img;
                        $avatar = '<img class="rounded-circle" src="'.$file.'.jpg" alt="avatar" widt="45" height="45">';
                        echo '<tr>';
                        echo '<td>'.($index + 1).'</td>';
                        echo '<td>'.$avatar.'</td>';
                        echo '<td>'.$maestro->name.'</td>';
                        echo '<td>'.$maestro->lastname.'</td>';
                        echo '<td>'.$maestro->user_email.'</td>';
                        echo '<td>'.$maestro->user_phone.'</td>';
                        echo '<td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-info btn-raised editTeacher mr-3"
                                        data-teacher="'.$maestro->user_id.'">
                                            <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-danger btn-raised btn_delete_teacher"
                                        data-teacher="'.$maestro->user_id.'"
                                        data-name="'.$maestro->name.' '.$maestro->lastname.'"
                                        data-toggle="tooltip"
                                        title="Delete teacher"><i class="fa fa-trash"></i></button>
                             </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
            echo '</div>';

            // Páginacion
            echo '<div class="row">';
                echo '<div class="col-sm-12 text-center">';
                    echo $paginacion;
                echo '</div>';
            echo '</div>';
        } else {
             echo '<h5 class="text-center text-muted">No hay maestros registrados aún.</h5>';
        }
    }

    public static function getTeacher($id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT user_id, name, lastname, user_name, user_phone, user_email, user_access_code
                FROM users 
                WHERE user_id = :id AND user_type = 3;";
        $query = $database->prepare($sql);
        $query->execute(array(':id' => $id));

        return $query->fetch();
    }

    public static function validateUsername($username){
        $database = DatabaseFactory::getFactory()->getConnection();
        $_sql = $database->prepare("SELECT user_name FROM users WHERE user_name = :username LIMIT 1;");
        $_sql->execute(array(':username' => $username));

        if ($_sql->rowCount() > 0) {
            $user = $_sql->fetch();
            return array(
                        'exists' => true, 
                        'message' => '&#x2718; El numbre de usuario: <strong>' .$user->user_name. '</strong> ya existe, elija otro por favor!');
        }
        return array('exists' => false);
    }

    public static function validateEmail($email){
        $database = DatabaseFactory::getFactory()->getConnection();
        $_sql = $database->prepare("SELECT user_email FROM users WHERE user_email = :email LIMIT 1;");
        $_sql->execute(array(':email' => $email));

        if ($_sql->rowCount() > 0) {
            $user = $_sql->fetch();
            return array(
                        'exists' => true, 
                        'message' => '&#x2718; El email: <strong>' .$user->user_email. '</strong> ya existe, elija otro por favor!');
        }
        return array('exists' => false);
    }

    public static function updateTeacher($teacher, $name, $lastname, $useremail, $username, $phone, $password){
        $database = DatabaseFactory::getFactory()->getConnection();
        $user_password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = $database->prepare("UPDATE users SET name       = :name,
                                                      lastname   = :lastname,
                                                      user_name  = :username,
                                                      user_phone = :phone,
                                                      user_password_hash = :user_password,
                                                      user_email = :useremail,
                                                      user_access_code   = :user_code
                                    WHERE user_id = :teacher
                                      AND user_type = 3;");
        $update = $query->execute(array(':name'      => $name,
                                        ':lastname'  => $lastname,
                                        ':username'  => $username,
                                        ':phone'     => $phone,
                                        ':user_password' => $user_password_hash,
                                        ':useremail' => $useremail,
                                        ':user_code' => $password,
                                        ':teacher'   => $teacher));

        if ($update) {
            if (isset($_FILES['avatar_file']) && $_FILES['avatar_file'] !== null && $_FILES['avatar_file']['name'] !== '') {
                PhotosModel::createAvatar($teacher, 'teacher', $_FILES['avatar_file']);
            }
            Session::add('feedback_positive','Datos del maestro actualizados correctamente.');
        } else {
            Session::add('feedback_negative','No se pudo actualizar los datos del maestro.');
        }
    }

    public static function deleteTeacher($teacher){
        $database = DatabaseFactory::getFactory()->getConnection();

        $delete  = $database->prepare("UPDATE users SET user_deleted = 1 WHERE user_id = :user;");
        $deleted = $delete->execute(array(':user' => $teacher));

        return $deleted;
    }

}
