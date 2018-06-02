<?php

class ImportarModel
{
	public static function getClasesList(){
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT * 
						        	 FROM naatikdb.classes as c, naatikdb.naatik_course as n,
						        	      naatikdb.groups_nc as g, naatikdb.schedule as s
						        	 WHERE c.id_course   = n.id_course
						        	   AND c.id_group    = g.id_group
						        	   AND c.id_schedule = s.id_schedule
						        	 ORDER BY c.id_class;");
        $query->execute();

        $clases = $query->fetchAll();
        foreach ($clases as $clase) {
        	$dias = self::convertDays($clase->days);
        	switch ((int)$clase->id_course) {
        		case 1: $id_curso = 1; break;
        		case 2: $id_curso = 3; break;
        		case 3: $id_curso = 4; break;
        		case 4: $id_curso = 5; break;
        	}
        	continue;

        	CursoModel::addNewClass(
        		$id_curso, $clase->id_group, 
        		$clase->date_init, $clase->date_end, 
        		$clase->year, $dias, 
        		$clase->hour_init, $clase->hour_end, 
        		$clase->normal_cost, $clase->promo_cost, $clase->inscription_cost, NULL);
        	// H::p($dias);
        }
        // exit();
        H::p($clases);
	}

	public static function importGroups(){
		return true;
		$database = DatabaseFactory::getFactory()->getConnection();
		$grupos = $database->prepare("SELECT * FROM naatikdb.groups_nc;");
		$grupos->execute();
		$grupos = $grupos->fetchAll();
		foreach ($grupos as $grupo) {
			$nombre = strtoupper(strtolower($grupo->group));
			$save = $database->prepare("INSERT INTO groups(group_name) VALUES(:group_name);");
			$save->execute(array(':group_name' => $nombre));

			if ($save->rowCount() < 1) {
				var_dump('Fallo importacion de: ', $grupo->group);
				return false;
			}
		}
		return true;
	}

	public static function getTeachersList(){
		return true;
		$database   = DatabaseFactory::getFactory()->getConnection();
		$sql = $database->prepare("SELECT t.id_teacher, t.name_te, t.surname_te, t.photo_te,
										  u.username, u.password
									FROM naatikdb.teacher as t, naatikdb.user as u
									WHERE t.iduser != 0
									  AND t.iduser  = u.id_user;");
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$maestros = $sql->fetchAll();
			foreach ($maestros as $maestro) {
				$user_password_hash = password_hash($maestro->password, PASSWORD_DEFAULT);
				$user_activation_hash = sha1(uniqid(mt_rand(), true));
				$user_email = strtolower($maestro->username) . '@test.com';

				$save = RegistrationModel::writeNewUserToDatabase(
											$maestro->name_te, $maestro->surname_te, 3, 
											$maestro->username, $user_password_hash, $user_email, time(), 
											$user_activation_hash, $maestro->password);
				if (!$save) {
					var_dump('Error con Maestro: ', $maestro->name_t);
					return false;
				}
			}
			return true;
		}
	}

	public static function getRepeatedStudents(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$sql = $database->prepare("SELECT id_student, name_s, surname1_s, surname2_s FROM naatikdb.student ORDER BY surname1_s");
		$sql->execute();

		$repetidos = [];
		$revisados = [];
		$cont = 0;
		foreach ($sql->fetchAll() as $row) {
			$name     = '%'.trim($row->name_s).'%';
			$surname  = '%'.trim($row->surname1_s).'%';
			$lastname = '%'.trim($row->surname2_s).'%';

			if (!in_array($row->id_student, $revisados)) {
				$query = $database->prepare("SELECT id_student, name_s, surname1_s, surname2_s, status, id_tutor 
										   FROM naatikdb.student
										   WHERE name_s     LIKE :name
											 AND surname1_s LIKE :surname
											 AND surname2_s LIKE :lastname;");
				$query->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));

				if ($query->rowCount() > 1) {
					$alumnos = $query->fetchAll();
					array_push($repetidos, $alumnos);
					foreach ($alumnos as $alumno) {
						array_push($revisados, $alumno->id_student);
					}
				}
			}
		}
		self::procesarRepetidos($repetidos);
	}

	public static function procesarRepetidos($lista){
		$database = DatabaseFactory::getFactory()->getConnection();
		foreach ($lista as $repetidos) {
			echo '<div class="table-responsive mb-2">';
                    echo '<table class="table table-striped table-sm table-bordered">';
                    echo '<thead>';
                        echo '<tr class="bg-info">';
                        echo '<th class="text-center">ID</th>';
                        echo '<th class="text-center">Nombre</th>';
                        echo '<th class="text-center">Apellido Pat.</th>';
                        echo '<th class="text-center">Apellido Mat.</th>';
                        echo '<th class="text-center">Estado</th>';
                        echo '<th class="text-center">Tutor</th>';
                        echo '<th class="text-center">Grupo</th>';
                        echo '<th class="text-center">SEP</th>';
                        echo '<th class="text-center">A. Info</th>';
                        echo '<th class="text-center">E. Info</th>';
                        echo '<th class="text-center">Beca</th>';
                        echo '<th class="text-center">Opciones</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach ($repetidos as $alumno) {
                        	$tutor = $database->prepare("SELECT CONCAT_WS(' ', name_t, surname1_t, surname2_t) as nombre 
						                        		 FROM naatikdb.tutor
						                        		 WHERE id_tutor = :tutor
						                        		 LIMIT 1;");
							$tutor->execute(array(':tutor' => $alumno->id_tutor));
							$tutor = $tutor->fetch();

							
							$_sql = $database->prepare("SELECT id_student, reg_sep, id_classes 
														FROM naatikdb.academic_info WHERE id_student = :student");
							$_sql->execute(array(':student' => $alumno->id_student));
							$info = $_sql->rowCount();

							$infoS = 0;
							$sep = 0;
							$grupo=null;
							if ($info > 0) {
								$aka = $_sql->fetch();
								$sep = $aka->reg_sep;
								$sqlS = $database->prepare("SELECT id_sep FROM naatikdb.sep WHERE id_sep = :sep");
								$sqlS->execute(array(':sep' => $aka->reg_sep));
								$infoS = $sqlS->rowCount();

								$sqlG = $database->prepare("SELECT cu.course, g.group 
															FROM naatikdb.classes as c, naatikdb.naatik_course as cu, naatikdb.groups_nc as g
															WHERE c.id_class = :clase
															  AND c.id_course = cu.id_course
															  AND c.id_group  = g.id_group
															");
								$sqlG->execute(array(':clase' => $aka->id_classes));

								if ($sqlG->rowCount() > 0) {
									$infoG = $sqlG->fetch();
									$grupo = $infoG->course .' '. $infoG->group;
								}
							}

							$sql = $database->prepare("SELECT id_st FROM naatikdb.info_extrast WHERE id_st = :student");
							$sql->execute(array(':student' => $alumno->id_student));
							$infoE = $sql->rowCount();

							$sqlB = $database->prepare("SELECT id_student FROM naatikdb.scholar WHERE id_student = :student");
							$sqlB->execute(array(':student' => $alumno->id_student));
							$infoB = $sqlB->rowCount();

                            echo '<tr>';
                            echo '<td class="text-center">'.$alumno->id_student.'</td>'; 
                            echo '<td class="text-center">
									<input type="text" id="'.$alumno->id_student.'name" value="'.$alumno->name_s.'">
                                  </td>';
                            echo '<td class="text-center">
									<input type="text" id="'.$alumno->id_student.'surname" value="'.$alumno->surname1_s.'">
                                  </td>';
                            echo '<td class="text-center">
									<input type="text" id="'.$alumno->id_student.'lastname" value="'.$alumno->surname2_s.'">
                                  </td>';
                            echo '<td class="text-center">'.$alumno->status.'</td>';
                            echo '<td class="text-center">
									<input type="text" id="'.$alumno->id_student.'tutor" value="'.$tutor->nombre.'">
                                  </td>';
                            echo '<td class="text-center">'.$grupo.'</td>';
                            echo '<td class="text-center">'.$infoS.'</td>';
                            echo '<td class="text-center">'.$info.'</td>';
                            echo '<td class="text-center">'.$infoE.'</td>';
                            echo '<td class="text-center">'.$infoB.'</td>';
                            echo '<td class="text-center">';
                            	if((int)$info > 0 && (int)$infoE > 0 && (int)$infoS > 0 && (int)$infoB > 0 && $grupo != null){
									echo '<button type="button" class="btn btn-sm btn-info btn_update" 
									              id="'.$alumno->id_student.'"><i class="fa fa-save"></i></button>';
                            	} else {
                            		echo '<button type="button" 
                            					  class="btn btn-sm btn-danger btn_delete"
                            					  data-sep="'.$sep.'"
									              id="'.$alumno->id_student.'"><i class="fa fa-trash"></i></button>';
                            	}
                            echo  '</td>';
                            echo '</tr>';   
                        }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
		}
	}

	public static function updateNameStudent($student, $name, $surname, $lastname){
		$database = DatabaseFactory::getFactory()->getConnection();
		$sql = $database->prepare("UPDATE naatikdb.student 
								   SET name_s = :name,
									   surname1_s = :surname,
									   surname2_s = :lastname
								   WHERE id_student = :student;");
		$sql->execute(array(':name' => trim($name), 
							':surname' => trim($surname), 
							':lastname' => trim($lastname), 
							':student' => $student));
	}

	public static function deleteStudent($student, $sep){
		$database = DatabaseFactory::getFactory()->getConnection();
		// Alumnos
		$sqlA = $database->prepare("DELETE FROM naatikdb.student WHERE id_student = :student;");
		$sqlA->execute(array(':student' => $student));

		// Academico
		$sqlA = $database->prepare("DELETE FROM naatikdb.academic_info WHERE id_student = :student;");
		$sqlA->execute(array(':student' => $student));

		// Extra
		$sqlA = $database->prepare("DELETE FROM naatikdb.info_extrast WHERE id_st = :student;");
		$sqlA->execute(array(':student' => $student));

		// Sep
		$sqlA = $database->prepare("DELETE FROM naatikdb.sep WHERE id_sep = :sep;");
		$sqlA->execute(array(':sep' => $sep));

		// Becas
		$sqlA = $database->prepare("DELETE FROM naatikdb.scholar WHERE id_student = :student;");
		$sqlA->execute(array(':student' => $student));

		// Pagos
		$sqlA = $database->prepare("DELETE FROM naatikdb.pays WHERE id_student = :student;");
		$sqlA->execute(array(':student' => $student));
	}






	public static function importStudents($page) {
		H::getLibrary('paginadorLib');
		$paginator  = new \Paginador();
		$filas      = 25;
		$page       = (int)$page;
		$database   = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("SELECT * 
									 FROM naatikdb.student 
									 WHERE exportado=0 
									 ORDER BY exportado, surname1_s;");
		$query->execute();
		$students = [];
		if ($query->rowCount() > 0) {
			$alumnos = $query->fetchAll();
			$items   = $paginator->paginar($alumnos, $page, $filas);
			foreach ($items as $row) {
				$txt = array('.jpg', '.jpeg', '.JPG', '.JPEG', '.png', '.PNG', '.gif');
				$avatar_name = str_replace($txt, '', $row->photo_s);
				$avatar = str_replace('default', $row->sexo, $avatar_name);
				$students[$row->id_student] = new stdClass();
				$students[$row->id_student]->xported        = $row->exportado;
				$students[$row->id_student]->student_id     = $row->id_student;
				$students[$row->id_student]->name           = ucwords(strtolower($row->name_s));
				$students[$row->id_student]->surname        = ucwords(strtolower($row->surname1_s));
				$students[$row->id_student]->lastname       = ucwords(strtolower($row->surname2_s));
				$students[$row->id_student]->birthdate      = $row->birthday;
				$students[$row->id_student]->age            = $row->age;
				$students[$row->id_student]->genre          = $row->sexo;
				$students[$row->id_student]->civil_status   = $row->edo_civil;
				$students[$row->id_student]->cellphone      = $row->cellphone;
				$students[$row->id_student]->reference      = $row->reference;
				$students[$row->id_student]->isckness       = $row->sickness;
				$students[$row->id_student]->medication     = $row->medication;
				$students[$row->id_student]->avatar         = strtolower($avatar);
				$students[$row->id_student]->description    = $row->comment_s;
				$students[$row->id_student]->homestay       = $row->homestay;
				$students[$row->id_student]->status         = $row->status;
				$students[$row->id_student]->curso          = self::getCourse($row->id_student);
				$students[$row->id_student]->tutor          = self::getTutor($row->id_tutor);
				$students[$row->id_student]->ubication      = self::getUbicationAddress($row->id_tutor);
				$students[$row->id_student]->academic_data  = self::getAcademicInfo($row->id_student);
				$students[$row->id_student]->details        = self::getExtraInfo($row->id_student);
				$students[$row->id_student]->pay_data       = self::getPayHistoric($row->id_student);
				$students[$row->id_student]->scholar_data   = self::getBecasHistoric($row->id_student);
			}

			$counter    = $page > 0 ? (($page*$filas)-$filas) + 1 : 1;
			$paginacion = $paginator->getView('pagination_ajax', 'students');
			self::displayStudentsList($students, $counter);
			echo $paginacion;
		}
	}

	public static function displayStudentsList($alumnos, $cont){
		echo '<div class="table-responsive">';
			echo '<table class="table table-sm table-hover table-striped">';
				echo '<thead>';
					echo '<tr class="bg-info">';
						echo '<th>No.</th>';
						echo '<th>Foto</th>';
						echo '<th>Nombre</th>';
						echo '<th>Tutor</th>';
						echo '<th>Grupo</th>';
						echo '<th>Estatus</th>';
						echo '<th class="text-center">Opciones</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach ($alumnos as $alumno){
						echo '<tr>';
							echo '<td>'.($cont++).'</td>';
							echo '<td>'.$alumno->avatar.'.jpg</td>';
							echo '<td>'.$alumno->surname.' '.$alumno->lastname.' '.$alumno->name.'</td>';
							echo '<td>';
								if (count($alumno->tutor) > 0) {
									echo $alumno->tutor['name'].' '.$alumno->tutor['surname'];
								} else {
									echo ' - - - - ';
								}
							echo '</td>';
							echo '<td>'.$alumno->curso.'</td>';
							echo '<td>'.$alumno->status.'</td>';
							echo '<td class="text-center">';
							if ($alumno->xported === "0"){
								echo '<button type="button" class="btn btn-sm btn-second btn_import" id="'.$alumno->student_id.'">Importar</button>';
							}else {
								echo '<button type="button" class="btn btn-sm btn-warning">Importado</button>';
							}
							echo '</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
	}

	public static function getTutor($tutor){
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT * FROM naatikdb.tutor 
									 WHERE id_tutor = :tutor
									   AND name_t != 'N/A'
									   AND name_t NOT LIKE '%---%'
									   AND surname1_t != 'N/A'
									   AND surname2_t != 'N/A'
									 LIMIT 1;");
		$query->execute(array(':tutor' => $tutor));

		$datos = NULL;
		if ($query->rowCount() > 0) {
			$datos = array();
			$addres = array();
			$tutor = $query->fetch();
			$datos['id']               = $tutor->id_tutor;
			$datos['name']             = ucwords(strtolower($tutor->name_t));
			$datos['surname']          = ucwords(strtolower($tutor->surname1_t));
			$datos['lastname']         = ucwords(strtolower($tutor->surname2_t));
			$datos['job']              = $tutor->job;
			$datos['phone']            = $tutor->phone;
			$datos['cellphone']        = $tutor->cellphone_t;
			$datos['phone_alt']        = $tutor->phone_alt;
			$datos['relationship']     = $tutor->relationship;
			$datos['relationship_alt'] = $tutor->relationship_alt;

			$mapa = self::getUbicationAddress($tutor->id_tutor);
			$addr = explode(',', $tutor->address_t);
			$address['street']    = $addr[0];
			$address['number']    = $addr[1];
			$address['between']   = $addr[2];
			$address['colony']    = $addr[3];
			$address['latitud']   = '19.57789189450819';
			$address['longitud']  = '-88.04557564999999';
			if ($mapa !== false) {
				$address['latitud']   = $mapa->latitud;
				$address['longitud']  = $mapa->longitud;
			}
			$datos['direccion']   = $address;
		}

		return $datos;
	}

	public static function getCourse($student){
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT nc.course, ng.group
									 FROM naatikdb.academic_info as ai, 
										  naatikdb.classes as c, 
										  naatikdb.naatik_course as nc, 
										  naatikdb.groups_nc as ng
									 WHERE ai.id_student = :student
									   AND ai.id_classes = c.id_class
									   AND c.id_course   = nc.id_course
									   AND c.id_group    = ng.id_group
									 LIMIT 1");
		$query->execute(array(':student' => $student));
		if ($query->rowCount() > 0) {
			$data = $query->fetch();
			$curso = $data->course .' '. $data->group;
			return $curso;
		}

		return false;
	}

	public static function getUbicationAddress($user){
		$database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("SELECT c.lat as latitud, c.long as longitud
									 FROM naatikdb.croquis as c
									 WHERE c.idtutor = :tutor LIMIT 1;");
		$query->execute(array(':tutor' => $user));

		if ($query->rowCount() > 0) {
			return $query->fetch();
		}

		return false;
	}

	public static function getAcademicInfo($student) {
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT *
									 FROM naatikdb.academic_info
									 WHERE id_student = :student
									 LIMIT 1");
		$query->execute(array(':student' => $student));
		$data = NULL;
		if ($query->rowCount() > 0) {
			$data = array();
			$info = $query->fetch();
			$data['ocupation']    = $info->ocupation;
			$data['workplace']    = $info->workplace;
			$data['studies']      = $info->studies;
			$data['level']        = $info->level;
			$data['prior_course'] = $info->prev_course;
			$data['sep']          = self::getSEPInfo($info->reg_sep);
			$data['date_init']    = $info->date_init_s;
			$date_baja   = $info->date_bajaSt === '0000-00-00' ? NULL : $info->date_bajaSt;
			$data['date_baja']    = $date_baja;
			$date_egreso = $info->date_egreso === '0000-00-00' ? NULL : $info->date_egreso;
			$data['date_egreso']  = $date_egreso;
		}

		return $data;
	}

	public static function getSEPInfo($id_sep){
		$database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("SELECT * FROM naatikdb.sep 
									 WHERE id_sep = :sep AND issep = 'si' LIMIT 1;");
		$query->execute(array(':sep' => $id_sep));

		if ($query->rowCount() > 0) {
			return $query->fetch();
		}
		return false;
	}

	public static function getExtraInfo($student) {
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT reg_nacimiento as acta, convenio, facturacion
									 FROM naatikdb.info_extrast
									 WHERE id_st = :student
									 LIMIT 1");
		$query->execute(array(':student' => $student));
		if ($query->rowCount() > 0) {
			return $query->fetch();
		}

		return false;
	}

	public static function getPayHistoric($student){
		$database = DatabaseFactory::getFactory()->getConnection();
		$sql = $database->prepare("SELECT * FROM naatikdb.pays WHERE id_student = :student;");
		$sql->execute(array(':student' => $student));

		$data = array();
		if ($sql->rowCount() > 0) {
			$pagos = $sql->fetchAll();
			foreach ($pagos as $pago) {
				$data['ene'] = $pago->jan;
				$data['feb'] = $pago->feb;
				$data['mar'] = $pago->mar;
				$data['abr'] = $pago->apr;
				$data['may'] = $pago->may;
				$data['jun'] = $pago->jun;
				$data['jul'] = $pago->jul;
				$data['ago'] = $pago->aug;
				$data['sep'] = $pago->sep;
				$data['oct'] = $pago->oct;
				$data['nov'] = $pago->nov;
				$data['dic'] = $pago->dece;
				$data['anio'] = $pago->year_pay;
				$data['ciclo']  = $pago->ciclo;
				$data['becado']   = $pago->becado;
				$data['comentario'] = $pago->comment;
			}
		}

		return $data;
	}

	public static function getBecasHistoric($student){
		$database = DatabaseFactory::getFactory()->getConnection();

		$sql = $database->prepare("SELECT request, togrant, date_scholar 
								   FROM naatikdb.scholar
								   WHERE id_student = :student;");
		$sql->execute(array(':student' => $student));

		if ($sql->rowCount() > 0) {
			return $sql->fetchAll();
		}
		 return false;
	}





	public static function importarAlumno($student) {
		$database = DatabaseFactory::getFactory()->getConnection();
		$commit   = true;
		$_sql = $database->prepare("SELECT * FROM naatikdb.student WHERE id_student = :student LIMIT 1;");
		$_sql->execute(array(':student' => $student));
		$alumno = $_sql->fetch();

		// Evitar Duplicidad
		$name     = trim(ucwords(strtolower($alumno->name_s)));
		$surname  = trim(ucwords(strtolower($alumno->surname1_s)));
		$lastname = trim(ucwords(strtolower($alumno->surname2_s)));
		$verify = $database->prepare("SELECT student_id
									  FROM students 
									  WHERE name     = :name
										AND surname  = :surname
										AND lastname = :lastname
									  LIMIT 1;");
		$verify->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));
		if ($verify->rowCount() > 0) {
			return array('success' => true, 'message' => '&#x2713; I M P O R T A D O!!');
		}

        $database->beginTransaction();
        try{
        	$update = $database->prepare("UPDATE naatikdb.student SET exportado = 1 WHERE id_student = :student;");
			$imported = $update->execute(array(':student' => $alumno->id_student));

			if ($imported) {
				//  =  =  =  =  =  = Importar Tutor  =  =  =  =  =  =  =
				$getTutor = $database->prepare("SELECT * FROM naatikdb.tutor WHERE id_tutor = :tutor LIMIT 1;");
				$getTutor->execute(array(':tutor' => $alumno->id_tutor));

				$tutor_id = null;
				if ($getTutor->rowCount() > 0) {
					$tutor = $getTutor->fetch();
					$tutor_name = trim($tutor->name_t);
					$tutor_surname = trim($tutor->surname1_t);
					$tutor_lastname = trim($tutor->surname2_t);
					$latitud  = 19.57789189450819;
					$longitud = -88.04557564999999;

					$mapa = $database->prepare("SELECT c.lat as latitud, c.long as longitud
												 FROM naatikdb.croquis as c
												 WHERE c.idtutor = :tutor LIMIT 1;");
					$mapa->execute(array(':tutor' => $tutor->id_tutor));


					if ($mapa->rowCount() > 0) {
						$mapa = $mapa->fetch();
						$latitud  = $mapa->latitud;
						$longitud = $mapa->longitud;
					}
					$addr = explode(',', $tutor->address_t);

					$street   = trim(str_replace('Calle:', '', $addr[0]));
					$number   = trim($addr[1]);
					$between  = trim(str_replace('Entre:', '', $addr[2]));
					$colony   = trim(str_replace('Col:', '', $addr[3]));


					// Si no cumple la condición, el alumno no tiene tutor
					if ($tutor_name != 'N/A' && $tutor_surname != 'N/A' && $tutor_lastname != 'N/A' && $tutor_name != '----' && $tutor_surname != '----' && $tutor_lastname != '----') {
						$verifyTutor = $database->prepare("SELECT id_tutor
														  FROM tutors 
														  WHERE namet = :name
															AND surnamet = :surname
															AND lastnamet = :lastname
														  LIMIT 1;");
						$verifyTutor->execute(array(
												':name' => $tutor_name, 
												':surname' => $tutor_surname, 
												':lastname' => $tutor_lastname));

						if ($verifyTutor->rowCount() > 0) {
							// Ya esta registrado
							$tutor_id = $verifyTutor->fetch()->id_tutor;
						} else {
							// Registrar como nuevo
							$save_tutor  =  "INSERT INTO tutors(namet, surnamet, lastnamet, job, cellphone, phone,
															   relationship, phone_alt, relationship_alt)
														VALUES(:name, :surname, :lastname, :job, :cellphone, :phone,
															   :relation, :phone_alt, :relation_alt);";
							$query = $database->prepare($save_tutor);
							$query->execute(array(
												':name'         => ucwords(strtolower($tutor_name)),
												':surname'      => ucwords(strtolower($tutor_surname)),
												':lastname'     => ucwords(strtolower($tutor_surname)),
												':job'          => trim($tutor->job),
												':cellphone'    => trim($tutor->cellphone_t),
												':phone'        => trim($tutor->phone),
												':relation'     => $tutor->relationship,
												':phone_alt'    => trim($tutor->phone_alt),
												':relation_alt' => $tutor->relationship_alt
											));
							if ($query->rowCount() > 0) {
								$tutor_id = $database->lastInsertId();

								$croquis = $database->prepare("INSERT INTO address(user_id, user_type, street, st_number, 
																			   st_between, colony, city, zipcode, state, 
																			   country, latitud, longitud)
													VALUES(:user, 1, :street, :st_number, :st_between, :colony,
														   :city, :zipcode, :state, :country, :latitud, :longitud);");
								$croquis->execute(array(
									':user'       => $tutor_id,
									':street'     => $street,
									':st_number'  => $number,
									':st_between' => $between,
									':colony'     => $colony,
									':city'       => 'Felipe Carrillo Puerto',
									':zipcode'    => 77200,
									':state'      => 'Quintana Roo',
									':country'    => 'México',
									':latitud'    => $latitud,
									':longitud'   => $longitud)
							    );
							} else {
								$commit = false;
							}
						}
					}
				} else {
					$commit = false;
				}

				//  =  =  =  =  =  =  Importar Alumno  =  =  =  =  =  =
				if ($commit) {
					$txt = array('.jpg', '.jpeg', '.JPG', '.JPEG', '.png', '.PNG', '.gif');
					$avatar_name = str_replace($txt, '', $alumno->photo_s);
					$avatar = str_replace('default', $alumno->sexo, $avatar_name);

					switch ($alumno->status) {
						case 'activo':   $estado = 1; break;
						case 'inactivo': $estado = 1; break;
						case 'baja':     $estado = 0; break;
						default: $estado = 2; break;
					}

					$edad = H::getAge($alumno->birthday);
					$IDtutor = $tutor_id === null ? 0 : $tutor_id;
					$sql =  $database->prepare("INSERT INTO students(id_tutor, name, surname, lastname,
																	 birthday, age, genre, edo_civil,
																	 cellphone, reference, sickness,
																	 medication, avatar, comment_s, status)
															 VALUES(:tutor, :name, :surname, :lastname,
																	:birthday, :age, :genre, :edo_civil,
																	:cellphone, :reference, :sickness,
																	:medication, :avatar, :comment_s, :status);");
					$sql->execute(array(':tutor'      => $IDtutor,
										':name'       => $name,
										':surname'    => $surname,
										':lastname'   => $lastname,
										':birthday'   => $alumno->birthday,
										':age'        => $edad,
										':genre'      => $alumno->sexo,
										':edo_civil'  => $alumno->edo_civil,
										':cellphone'  => trim($alumno->cellphone),
										':reference'  => trim($alumno->reference),
										':sickness'   => trim($alumno->sickness),
										':medication' => trim($alumno->medication),
										':avatar'     => $avatar,
										':comment_s'  => $alumno->comment_s,
										':status'     => $estado));
					
					
					if ($sql->rowCount() > 0) {
						$id_alumno = $database->lastInsertId();

						if ($tutor_id == null) {
							$croquis = $database->prepare("INSERT INTO address(user_id, user_type, street, st_number, 
																		   st_between, colony, city, zipcode, state, 
																		   country, latitud, longitud)
												VALUES(:user, 2, :street, :st_number, :st_between, :colony,
													   :city, :zipcode, :state, :country, :latitud, :longitud);");
							$croquis->execute(array(
								':user'       => $id_alumno,
								':street'     => $street,
								':st_number'  => $number,
								':st_between' => $between,
								':colony'     => $colony,
								':city'       => 'Felipe Carrillo Puerto',
								':zipcode'    => 77200,
								':state'      => 'Quintana Roo',
								':country'    => 'México',
								':latitud'    => $latitud,
								':longitud'   => $longitud
							));
						}

						$queryBeca = $database->prepare("SELECT *
													     FROM naatikdb.scholar
													     WHERE id_student = :student
													       AND request = 'si' OR togrant = 'si'
													     LIMIT 1");
						$queryBeca->execute(array(':student' => $alumno->id_student));

						if ($queryBeca->rowCount() > 0) {
							$beca = $queryBeca->fetch();
							$aplicante = $beca->request == 'si' ? 1 : 0;
							$becario   = $beca->togrant == 'si' ? 1 : 0;
							$fecha     = explode('-', $beca->date_scholar);
							$ciclo     = H::getCiclo($fecha[1]);

							$crearBeca = $database->prepare("INSERT INTO becas(student_id, applicant, granted,
																				year, ciclo, granted_at)
																			VALUES(:student, :aplicante, :becario, 
																					:anio, :ciclo, :f_becario);");
							$crearBeca->execute(array(':student' => $id_alumno, ':aplicante' => $aplicante, ':becario' => $becario,
														':anio' => $fecha[0], ':ciclo' => $ciclo, ':f_becario' => $beca->date_scholar));
						}


						$queryPago = $database->prepare("SELECT *
													     FROM naatikdb.pays
													     WHERE id_student = :student
													     LIMIT 1");
						$queryPago->execute(array(':student' => $alumno->id_student));

						if ($queryPago->rowCount() > 0) {
							$pagos = $queryPago->fetch();

							$crearPagos =  $database->prepare("INSERT INTO students_pays(student_id, 
																						 ene,
																						 feb,
																						 mar,
																						 abr,
																						 may,
																						 jun,
																						 jul,
																						 ago,
																						 sep,
																						 oct,
																						 nov,
																						 dic,
																						 year, 
																						 ciclo,
																						 comment)
				                                                     VALUES(:student, 
						                                                     :ene,
																			 :feb,
																			 :mar,
																			 :abr,
																			 :may,
																			 :jun,
																			 :jul,
																			 :ago,
																			 :sep,
																			 :oct,
																			 :nov,
																			 :dic, 
						                                                     :year, 
						                                                     :ciclo,
						                                                 	 :comment);");
				            $crearPagos->execute(array(':student' => $id_alumno,
					                                    ':ene'  => $pagos->jan,
					                                    ':feb'  => $pagos->feb,
					                                    ':mar'  => $pagos->mar,
					                                    ':abr'  => $pagos->apr,
					                                    ':may'  => $pagos->may,
					                                    ':jun'  => $pagos->jun,
					                                    ':jul'  => $pagos->jul,
					                                    ':ago'  => $pagos->aug,
					                                    ':sep'  => $pagos->sep,
					                                    ':oct'  => $pagos->oct,
					                                    ':nov'  => $pagos->nov,
					                                    ':dic'  => $pagos->dece,
					                                    ':year'    => $pagos->year_pay,
					                                    ':ciclo'   => $pagos->ciclo,
					                                	':comment' => $pagos->comment));
						}


						$queryData = $database->prepare("SELECT *
													     FROM naatikdb.academic_info
													     WHERE id_student = :student
													     LIMIT 1");
						$queryData->execute(array(':student' => $alumno->id_student));

						$queryInfo= $database->prepare("SELECT reg_nacimiento as acta, convenio, facturacion
														 FROM naatikdb.info_extrast
														 WHERE id_st = :student
														 LIMIT 1");
						$queryInfo->execute(array(':student' => $alumno->id_student));

						if ($queryData->rowCount() > 0 && $queryInfo->rowCount() > 0) {
							$detalle = $queryData->fetch();
							$extra   = $queryInfo->fetch();

							//Insert Student Details
							$details =  $database->prepare("INSERT INTO students_details(student_id, convenio, facturacion,
																						 homestay, acta_nacimiento, ocupation, 
																						 workplace, studies, lastgrade, 
																						 prior_course, prior_comments)
																	 VALUES(:student, :convenio, :invoice, :homestay,
																			:acta, :ocupation, :workplace, :studies,
																			:lastgrade, :prior_course, :prior_comments);");
							$details->execute(array(':student'         => $id_alumno,
													':convenio'        => $extra->convenio,
													':invoice'         => $extra->facturacion,
													':homestay'        => $alumno->homestay,
													':acta'            => $extra->acta,
													':ocupation'       => $detalle->ocupation,
													':workplace'       => $detalle->workplace,
													':studies'         => $detalle->studies,
													':lastgrade'       => $detalle->level,
													':prior_course'    => 1,
													':prior_comments'  => $detalle->prev_course
												));
							

							$date_start = $detalle->date_init_s;
							$date_down  = $detalle->date_bajaSt === '0000-00-00' ? NULL : $detalle->date_bajaSt;
							$date_end   = $detalle->date_egreso === '0000-00-00' ? NULL : $detalle->date_egreso;

							if ($details->rowCount() > 0) {
								$clases = array('3' => 1, '21' => 2, '23' => 3, '24' => 4, '25' => 5, '26' => 6, '27' => 7, '28' => 8, '29' => 9, '30' => 10, '31' => 11, '32' => 12, '33' => 13, '34' => 14, '35' => 15, '36' => 16, '37' => 17, '38' => 18, '39' => 19, '40' => 20, '42' => 21, '43' => 22, '45' => 23, '46' => 24, '47' => 5);

								$grupo = array_key_exists($detalle->id_classes, $clases) ? $clases[$detalle->id_classes] : null;
								$groups =  $database->prepare("INSERT INTO students_groups(class_id, student_id, date_begin, 
																							convenio, status, created_at)
																		 VALUES(:clase, :student, :date_begin, :convenio, 
																				 1, :created_at);");
								$groups->execute(array(':clase'      => $grupo,
														':student'       => $id_alumno,
														':convenio'     => $extra->convenio,
														':date_begin'   => $date_start,
														':created_at'   => $date_start));

								if ($groups->rowCount() < 1) {
									$commit = false;
								}
							} else {
								$commit = false;
							}
						} else {
							$commit = false;
							$message = 'El alumno no tiene informacion academica';
						}
					} else {
						$commit = false;
						$message = 'Error al guardar al alumno.';
					}
				} else {
					$commit = false;
					$message = "Error al guardar tutor o el alumno no tiene tutor.";
				}
			} else {
				$commit = false;
				$message = 'Error al importar alumno';
			}
                       
        } catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit) {
            $database->rollBack();
            return array(
	            	'success' => false, 
	            	'message' => '&#x2718; '.$message);
        }else {
            $database->commit();
            return array('success' => true, 'message' => '&#x2713; IMPORTADO!!');
        }
	}






	public static function importStudent($student) {
		$database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("SELECT * FROM naatikdb.student WHERE id_student = :student;");
		$imported = false;
		$query->execute(array(':student' => $student));
		$st_alumno = $student;
		$student = array();

		if ($query->rowCount() > 0) {
			$alumno = $query->fetch();

			// Evitar Repeticiones


			$tutor_data = self::getTutor($alumno->id_tutor);
			$tutor = 0;
			if ($tutor_data !== NULL) {
				$tutor = self::saveTutor($tutor_data);
				self::saveAddress($tutor, $tutor_data['direccion'], 1);
			}
			$imported = true;

			if ($imported) {
				$txt = array('.jpg', '.jpeg', '.JPG', '.JPEG', '.png', '.PNG', '.gif');
				$avatar_name = str_replace($txt, '', $alumno->photo_s);
				$avatar = str_replace('default', $alumno->sexo, $avatar_name);

				$student['name']         = ucwords(strtolower(trim($alumno->name_s)));
				$student['surname']      = ucwords(strtolower(trim($alumno->surname1_s)));
				$student['lastname']     = ucwords(strtolower(trim($alumno->surname2_s)));
				$student['birthdate']    = $alumno->birthday;
				$student['age']          = $alumno->age;
				$student['genre']        = $alumno->sexo;
				$student['civil_stat']   = $alumno->edo_civil;
				$student['cellphone']    = trim($alumno->cellphone);
				$student['reference']    = trim($alumno->reference);
				$student['sickness']     = trim($alumno->sickness);
				$student['medication']   = trim($alumno->medication);
				$student['avatar']       = strtolower($alumno->photo_s);
				$student['comment']      = $alumno->comment_s;
				switch ($alumno->status) {
					case 'activo':   $estado = 1; break;
					case 'inactivo': $estado = 1; break;
					case 'baja':     $estado = 0; break;
					default: $estado = 2; break;
				}
				$student['status']       = $estado;
				$student['tutor']        = $tutor;
				//Detalles del Alumno
				$student['homestay']     = $alumno->homestay;
				$student['academic']     = self::getAcademicInfo($alumno->id_student);
				$student['detail']       = self::getExtraInfo($alumno->id_student);

				$alumno_id = self::saveStudent($student);
				if ($alumno !== false && $tutor === 0) {
					$address = array();
					$addr = explode(',', $alumno->address_s);
					$address['street']        = $addr[0];
					$address['number']        = $addr[1];
					$address['between']       = $addr[2];
					$address['colony']        = $addr[3];
					$address['latitud']       = '19.57789189450819';
					$address['longitud']      = '-88.04557564999999';
					self::saveAddress($alumno_id, $tutor_data['direccion'], 2);
				}

				$change = $database->prepare("UPDATE naatikdb.student 
											  SET exportado = 1 
											  WHERE id_student = :student;");
				$change->execute(array(':student' => $alumno->id_student));

			}
			return $student;
			exit();

				$student['pay_data']      = self::getPayHistoric($alumno->id_student);
				$student['scholar_data']  = self::getBecasHistoric($alumno->id_student);
		}

		if (count($student) > 0) {
			self::saveStudent($student);
		} else {
			return false;
		}
	}

	public static function saveStudent($alumno){
		$database = DatabaseFactory::getFactory()->getConnection();
		$name     = $alumno['name'];
		$surname  = $alumno['surname'];
		$lastname = $alumno['lastname'];
		$tutor    = $alumno['tutor'];
		$verify = $database->prepare("SELECT student_id
									  FROM students 
									  WHERE id_tutor = :tutor
										AND name     = :name
										AND surname  = :surname
										AND lastname = :lastname
									  LIMIT 1;");
		$verify->execute(array(':tutor'    => $tutor, 
							   ':name'     => $name, 
							   ':surname'  => $surname, 
							   ':lastname' => $lastname));

		if ($verify->rowCount() >= 1) {
			return $verify->fetch()->student_id;
		}


		$commit = false;
		$database->beginTransaction();
		try{
			$edad = H::getAge($alumno['birthdate']);
			$sql =  $database->prepare("INSERT INTO students(id_tutor, name, surname, lastname,
															 birthday, age, genre, edo_civil,
															 cellphone, reference, sickness,
															 medication, avatar, comment_s, status)
													 VALUES(:tutor, :name, :surname, :lastname,
															:birthday, :age, :genre, :edo_civil,
															:cellphone, :reference, :sickness,
															:medication, :avatar, :comment_s, :status);");
			$sql->execute(array(':tutor'      => $tutor,
								':name'       => $name,
								':surname'    => $surname,
								':lastname'   => $lastname,
								':birthday'   => $alumno['birthdate'],
								':age'        => $edad,
								':genre'      => $alumno['genre'],
								':edo_civil'  => $alumno['civil_stat'],
								':cellphone'  => $alumno['cellphone'],
								':reference'  => $alumno['reference'],
								':sickness'   => $alumno['sickness'],
								':medication' => $alumno['medication'],
								':avatar'     => $avatar,
								':comment_s'  => $alumno['comment'],
								':status'     => $alumno['status']));
			
			if ($sql->rowCount() > 0) {
				$id_alumno = $database->lastInsertId();

				//Insert Student Details
				$detalle = $alumno['academic'];
				$extra   = $alumno['detail'];
				$details =  $database->prepare("INSERT INTO students_details(student_id, convenio, facturacion,
																			 homestay, acta_nacimiento, ocupation, 
																			 workplace, studies, lastgrade, 
																			 prior_course, prior_comments)
														 VALUES(:student, :convenio, :invoice, :homestay,
																:acta, :ocupation, :workplace, :studies,
																:lastgrade, :prior_course, :prior_comments);");
				$details->execute(array(':student'         => $id_alumno,
										':convenio'        => $extra->convenio,
										':invoice'         => $extra->facturacion,
										':homestay'        => $alumno['homestay'],
										':acta'            => $extra->acta,
										':ocupation'       => $detalle['ocupation'],
										':workplace'       => $detalle['workplace'],
										':studies'         => $detalle['studies'],
										':lastgrade'       => $detalle['level'],
										':prior_course'    => 1,
										':prior_comments'  => $detalle['prior_course']));
				if ($details->rowCount() > 0) {
					$groups =  $database->prepare("INSERT INTO students_groups(student_id, date_begin, 
																				convenio, state, created_at)
															 VALUES(:student, :date_begin, :convenio, 
																	 1, :created_at);");
					$groups->execute(array(':student'       => $id_alumno,
											':convenio'     => $extra->convenio,
											':date_begin'   => $detalle['date_init'],
											':created_at'   => $detalle['date_init']));
					if ($detalle['sep'] !== false) {
						$sep = $detalle['sep'];
						$save = $database->prepare("INSERT INTO students_sep(student_id, sep_code, date_register, 
																			 beca, status)
																 VALUES(:student, :sep_code, :date_register,
																		:beca, 1);");
						$save->execute(array(':student'       => $id_alumno,
											 ':sep_code'      => $extra->regis_num,
											 ':date_register' => $extra->date_incorporate,
											 ':beca'          => $extra->beca));

					}
					$commit = true;

				}
			}
		}catch (PDOException $e) {
			$commit = false;
		}

		if (!$commit) {
			$database->rollBack();
			return false;
		}else {
			$database->commit();
			return $id_alumno;
		} 
	}


	public static function saveTutor($tutor){
		$database = DatabaseFactory::getFactory()->getConnection();
		$commit = false;
		$id_tutor = 0;
		$name = ucwords(strtolower($tutor['name']));
		$surname = ucwords(strtolower($tutor['surname']));
		$lastname = ucwords(strtolower($tutor['lastname']));

		$verify = $database->prepare("SELECT id_tutor
									  FROM tutors 
									  WHERE namet = :name
										AND surnamet = :surname
										AND lastnamet = :lastname
									  LIMIT 1;");
		$verify->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));

		if ($verify->rowCount() >= 1) {
			return $verify->fetch()->id_tutor;
		}

		$database->beginTransaction();
		try{
			$save_tutor  = "INSERT INTO tutors(namet, surnamet, lastnamet, job, cellphone, phone,
											   relationship, phone_alt, relationship_alt)
										VALUES(:name, :surname, :lastname, :job, :cellphone, :phone,
											   :relation, :phone_alt, :relation_alt);";
			$query = $database->prepare($save_tutor);
			$query->execute(array(
								':name'         => $name,
								':surname'      => $surname,
								':lastname'     => $lastname,
								':job'          => $tutor['job'],
								':cellphone'    => $tutor['cellphone'],
								':phone'        => $tutor['phone'],
								':relation'     => $tutor['relationship'],
								':phone_alt'    => $tutor['phone_alt'],
								':relation_alt' => $tutor['relationship_alt']
							));
			if ($query->rowCount() > 0) {
				$id_tutor = $database->lastInsertId();
				$commit = true;
			}
		}catch (PDOException $e) {
			$commit = false;
		}

		if (!$commit) {
			$database->rollBack();
			return $id_tutor;
		}else {
			$database->commit();
			return $id_tutor;
		} 
	}

	public static function getNewTutorID(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$sql = $database->prepare("SELECT id_tutor FROM tutors ORDER BY id_tutor DESC LIMIT 1;");
		$sql->execute();
		return $sql->fetch()->id_tutor;
	}

	public static function saveAddress($user, $address, $user_type){
		$database = DatabaseFactory::getFactory()->getConnection();

		$verify = $database->prepare("SELECT user_id 
									  FROM address 
									  WHERE user_id = :user 
										AND user_type = :type 
									  LIMIT 1;");
		$verify->execute(array(':user' => $user, ':type' => $user_type));

		if ($user === 0 || $verify->rowCount() === 1) {
			return;
		}

		$sql = "INSERT INTO address(user_id, user_type, street, st_number, st_between, colony,
									city, zipcode, state, country, latitud, longitud)
							VALUES(:user, :user_type, :street, :st_number, :st_between, :colony,
								   :city, :zipcode, :state, :country, :latitud, :longitud);";
		$query = $database->prepare($sql);
		$query->execute(array(
			':user'       => $user,
			':user_type'  => $user_type,
			':street'     => $address['street'],
			':st_number'  => $address['number'],
			':st_between' => $address['between'],
			':colony'     => $address['colony'],
			':city'       => 'Felipe Carrillo Puerto',
			':zipcode'    => 77200,
			':state'      => 'Quintana Roo',
			':country'    => 'México',
			':latitud'    => $address['latitud'],
			':longitud'   => $address['longitud']));

		if ($query->rowCount() < 1) {
			return false;
		}            
	}

	public static function convertDays($dias){
		$lista = explode('-', $dias);
		$days = [];
		$index = 0;
		foreach ($lista as $dia) {
			switch ($dia) {
				case 'Lunes':     $id = 1; break;
				case 'Martes':    $id = 2; break;
				case 'Miercoles': $id = 3; break;
				case 'Jueves':    $id = 4; break;
				case 'Viernes':   $id = 5; break;
				case 'Sabado':    $id = 6; break;
			}

			$days[$index] = new stdClass();
			$days[$index]->dia = $id;
			$index++;
		}
		return $days;
	}

}
