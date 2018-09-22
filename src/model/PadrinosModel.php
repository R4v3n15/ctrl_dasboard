<?php

class PadrinosModel
{
	public static function getActiveSponsors() {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
                FROM sponsors
                WHERE sp_status = 1
                  AND deleted   = 0;";
        $query = $database->prepare($sql);
        $query->execute();    

        return $query->fetchAll();
    }

    public static function getInactiveSponsors() {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
                FROM sponsors
                WHERE sp_status = 0;";
        $query = $database->prepare($sql);
        $query->execute();    

        return $query->fetchAll();
    }

    public static function getAllSponsors($page){
        H::getLibrary('paginadorLib');
        $paginator = new \Paginador();
        $page      = (int)$page;
        $rows      = 10;
        $database  = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM sponsors WHERE deleted = 0;";
        $query = $database->prepare($sql);
        $query->execute();

        if ($query->rowCount() > 0) {
            $result     = $query->fetchAll();
            $sponsors   = $paginator->paginar($result, $page, $rows);
            $counter    = $page > 0 ? (($page*$rows)-$rows) + 1 : 1;
            $paginacion = $paginator->getView('pagination_ajax', 'sponsors');

            echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-hover table-striped">';
                    echo '<thead>';
                        echo '<tr class="info">';
                            echo '<th>ID</th>';
                            echo '<th>Nombre</th>';
                            echo '<th>Tipo</th>';
                            echo '<th>Correo Electronico</th>';
                            echo '<th>Descripción</th>';
                            echo '<th>Estatus</th>';
                            echo '<th>Alumnos Becados</th>';
                            echo '<th class="text-center">Opciones</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    $count = 1;
                    foreach ($sponsors as $sponsor) {
                        $status = $sponsor->sp_status === '1' ? 'Acivo' : 'Inactivo';

                        echo '<tr>';
                            echo '<td>'.$count++.'</td>';
                            echo '<td>'.$sponsor->sp_name. ' ' .$sponsor->sp_surname.'</td>';
                            echo '<td>'.$sponsor->sp_type.'</td>';
                            echo '<td>'.$sponsor->sp_email.'</td>';
                            echo '<td>'.$sponsor->sp_description.'</td>';
                            echo '<td>'.$status.'</td>';
                            echo '<td>Alumnos Becados</td>';
                            echo '<td class="text-center">';
                                echo '<button type="buton"
                                              title="Editar Datos"
                                              data-sponsor="'.$sponsor->sponsor_id.'" 
                                              class="btn btn-sm btn-raised btn-info btn_edit_sponsor mr-3"
                                              style="margin-right: 10px;">
                                                <i class="fa fa-edit"></i></button>';
                                echo '<button type="buton"
                                              title="Eliminar Datos"
                                              data-sponsor="'.$sponsor->sponsor_id.'"
                                              data-name="'.$sponsor->sp_name.' '.$sponsor->sp_surname.'" 
                                              class="btn btn-sm btn-raised btn-danger btn_delete_sponsor">
                                                <i class="fa fa-trash"></i></button>';
                            echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';

            // Paginación
            echo '<div class="row">';
                echo '<div class="col-sm-12 text-center">';
                    echo $paginacion;
                echo '</div>';
            echo '</div>';
        } else {
            echo '<h4 class="text-center text-naatik subheader">No hay Padrinos registrados aún.</h4>';
        }
    }

    public static function addNewSponsor($name, $surname, $type, $email, $description, $becario) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $today = H::getTime();
        $name = trim($name);
        $surname = trim($surname);
        
        $exist = GeneralModel::existSponsor($name, $surname);
        if ($exist) {
            return array('code' => 3, 'message' => 'Ya existe un padrino registrado como: '. $name.' '.$surname);
        }

        $sql = "INSERT INTO sponsors(sp_name, sp_surname, sp_type, sp_email, sp_description, created_at) 
                              VALUES(:name, :surname, :type, :email, :description, :today);";
        $query = $database->prepare($sql);
        $query->execute(array(':name'        => $name, 
                              ':surname'     => $surname, 
                              ':type'        => $type, 
                              ':email'       => $email, 
                              ':description' => $description,
                              ':today'       => $today));    

        if ($query->rowCount() > 0) {
            if ((int)$becario !== 0) {
                $padrino = $database->lastInsertId();
                $insert = $database->prepare("INSERT INTO becas(student_id, sponsor_id, period, granted_at) 
                                                          VALUES(:becario, :padrino, :periodo, :registro);");
                $insert->execute(array(':becario'  => $becario, 
                                       ':padrino'  => $padrino, 
                                       ':periodo'  => date('Y-m'), 
                                       ':registro' => H::getTime('Y-m-d')));
                if ($insert->rowCount() > 0) {
                    return array('code' => 1, 'message' => 'Información guadada correctamente!!');
                }
                return array('code' => 2, 'message' => 'Error al tratar de guardar becario, intente de nuevo');
            }
            return array('code' => 1, 'message' => 'Información guadada correctamente!!');
        }
        return array('code' => 0, 'message' => 'Error al tratar de guardar informacion, intente de nuevo');;
    }

    public static function getSponsor($sponsor) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query  =   $database->prepare("SELECT * FROM sponsors
                                        WHERE sponsor_id = :sponsor
                                        LIMIT 1;");
        $query->execute(array(':sponsor' => $sponsor));

        if ($query->rowCount() > 0) {
            return $query->fetch();
        }

        return null;
    }

    public static function updateSponsor($sponsor, $name, $surname, $type, $email, $description, $becario) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $_sql = $database->prepare("UPDATE sponsors 
                    		        SET sp_name        = :name,
                    			        sp_surname     = :surname,
                    			        sp_type        = :type,
                    			        sp_email       = :email,
                    			        sp_description = :description
                    			    WHERE sponsor_id = :sponsor;");
        $update = $_sql->execute(array(':sponsor'      => $sponsor,
                                        ':name'        => $name, 
                                        ':surname'     => $surname, 
                                        ':type'        => $type, 
                                        ':email'       => $email, 
                                        ':description' => $description));    

        return array('success' => $update, 'Message' => 'Datos actualizados');
    }

    public static function setActiveSponsor($sponsor) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE sponsors 
		        SET sp_status = 1,
			    WHERE ;";
        $query = $database->prepare($sql);
        $update = $query->execute(array(':name' => $name, ':surname' => $surname, ':type' => $type, ':email' => $email, ':description' => $description));    

        if ($update) {
        	return 1;
        }
        return 0;
    }

    public static function deleteSponsor($sponsor){
    	$database = DatabaseFactory::getFactory()->getConnection();
        $today = H::getTime();
    	$delete = $database->prepare("UPDATE sponsors 
                                      SET deleted = 1,
                                          deleted_at = :today
                                      WHERE sponsor_id = :sponsor;");
    	$deleted = $delete->execute(array(':today' => $today, ':sponsor' => $sponsor));

    	return array('success' => $deleted);
    }
}
