<?php

class MapaModel
{
	public static function getAddresStudent($student){
		$database = DatabaseFactory::getFactory()->getConnection();
		$_sql = $database->prepare("SELECT student_id, id_tutor, reference,
										   CONCAT_WS(' ', name, surname, lastname) as name
									FROM students 
									WHERE student_id = :student 
									LIMIT 1;");
		$_sql->execute(array(':student' => $student));

		if ($_sql->rowCount() > 0) {
			$alumno = $_sql->fetch();
			if ((int)$alumno->id_tutor !== 0) {
				$address = $database->prepare("SELECT * FROM address WHERE user_id = :tutor AND user_type = 1 LIMIT 1;");
				$address->execute(array(':tutor' => $alumno->id_tutor));
				$user = $alumno->id_tutor;
				$user_type = 1;
			} else {
				$address = $database->prepare("SELECT * FROM address WHERE user_id = :student AND user_type = 2 LIMIT 1;");
				$address->execute(array(':student' => $alumno->student_id));
				$user = $alumno->student_id;
				$user_type = 2;
			}
			$address = $address->fetch();

			$alumno->user      = $user;
			$alumno->user_type = $user_type;
			$alumno->address   = $address->id_address;
			$alumno->street    = $address->street;
			$alumno->number    = $address->st_number;
			$alumno->between   = $address->st_between;
			$alumno->colony    = $address->colony;
			$alumno->latitude  = $address->latitud;
			$alumno->longitud  = $address->longitud;
			
			return $alumno;
		}
		return null;
	}


	public static function updateStudentLocation($user, $user_type, $street, $number, $between, $colony, $latitud, $longitud){
		$database = DatabaseFactory::getFactory()->getConnection();

		// Validate
		$exists = $database->prepare("SELECT * FROM address WHERE user_id = :user AND user_type = :usertype LIMIT 1;");
		$exists->execute(array(':user' => $user, ':usertype' => $user_type));

		if ($exists->rowCount() > 0) {
			$update  =  $database->prepare("UPDATE address 
											SET street = :street,
												st_number = :numero,
												st_between = :entre,
												colony     = :colony,
												latitud    = :latitud,
												longitud   = :longitud
											WHERE user_id  = :user
											  AND user_type = :user_type;");
			$commit = $update->execute(array(
								':street'	=> $street,
								':numero'	=> $number,
								':entre'	=> $between,
								':colony'	=> $colony,
								':latitud'	=> $latitud,
								':longitud'	=> $longitud,
								':user'		=> $user,
								':user_type' => $user_type
			));

			if ($commit) {
				return array('success' => true, 'message' => 'Datos de ubicación del alumno actualizados con éxito!!');
			}

			return array(
					'success' => false, 
					'message' => 'Error al tratar de actualizar ubicación, intente de nuevo o reporte el error.');
		} else {
			array('success' => false, 'message' => 'Algo salio mal, intente de nuevo o reporte el problema.');
		}
	}

	public static function getMarks(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$_sql = $database->prepare("SELECT * FROM address LIMIT 20;");
		$_sql->execute();

		// Start XML file, create parent node
		$dom     = new DOMDocument("1.0");
		$node    = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);

		if ($_sql->rowCount() > 0) {
			header("Content-type: text/xml");
			$data = $_sql->fetchAll();

			foreach ($data as $row) {
				$node = $dom->createElement("marker");
			  	$newnode = $parnode->appendChild($node);

			  	$newnode->setAttribute("name", $row->colony);
			  	$newnode->setAttribute("address", $row->street.', '.$row->st_number);
			  	$newnode->setAttribute("lat", $row->latitud);
			  	$newnode->setAttribute("lng", $row->longitud);
			  	$newnode->setAttribute("type", $row->user_type);
			}
		}

		echo $dom->saveXML();
	}





	public static function parseToXML($htmlStr) {
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}

	public static function createXML(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$_sql = $database->prepare("SELECT * FROM address LIMIT 20;");
		$_sql->execute();

		if ($_sql->rowCount() > 0) {
			header("Content-type: text/xml");
			$data = $_sql->fetchAll();

			// Start XML file, echo parent node
			echo '<markers>';
			foreach ($data as $row) {
				// Add to XML document node
			    echo '<marker ';
			    echo 'name="' . parseToXML($row->colony) . '" ';
			    echo 'address="' . parseToXML($row->street) . '" ';
			    echo 'lat="' . $row->latitud. '" ';
			    echo 'lng="' . $row->longitud. '" ';
			    echo 'type="' . $row->user_type. '" ';
			    echo '/>';
			}
			// End XML file
			echo '</markers>';
		}
	}

}
