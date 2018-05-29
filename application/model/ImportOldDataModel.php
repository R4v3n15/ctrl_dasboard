<?php

class ImportOldDataModel
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
		$sql = $database->prepare("SELECT * FROM naatikdb.student ORDER BY surname1_s");
		$sql->execute();

		$repetidos = [];
		$revisados = [];
		$cont = 0;
		foreach ($sql->fetchAll() as $row) {
			$name     = '%'.trim($row->name_s).'%';
			$surname  = '%'.trim($row->surname1_s).'%';
			$lastname = '%'.trim($row->surname2_s).'%';
			$query = $database->prepare("SELECT id_student, name_s, surname1_s, surname2_s, status, id_tutor 
									   FROM naatikdb.student
									   WHERE name_s LIKE :name
										 AND surname1_s LIKE :surname
										 AND surname2_s LIKE :lastname;");
			$query->execute(array(':name' => $name, ':surname' => $surname, ':lastname' => $lastname));

			if ($query->rowCount() > 1) {
				if (!in_array($row->id_student, $revisados)) {
					$repetidos[$row->id_student] = new stdClass();
					$repetidos[$row->id_student]->items = $query->fetchAll();
					foreach ($repetidos[$row->id_student]->items as $value) {
						array_push($revisados, $value->id_student);
					}
				}
			}
		}
		return $repetidos;
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
				$students[$row->id_student]->avatar         = $row->photo_s;
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
			echo '<div class="row">';
				echo '<div class="col-sm-12 text-center">';
					echo $paginacion;
				echo '</div>';
			echo '</div>';
		}
	}

	public static function displayStudentsList($alumnos, $cont){
		echo '<div class="table-responsive">';
			echo '<table class="table table-bordered table-hover table-striped">';
				echo '<thead>';
					echo '<tr class="info">';
						echo '<th>#</th>';
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
							echo '<td>'.$alumno->avatar.'</td>';
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
				$update = $database->prepare("UPDATE naatikdb.student SET exportado = 1 WHERE id_student = :student;");
				$update->execute(array(':student' => $st_alumno));
				return true;
			}

			$tutor_data = self::getTutor($alumno->id_tutor);
			$tutor = 0;
			if ($tutor_data !== NULL) {
				$tutor = self::saveTutor($tutor_data);
				self::saveAddress($tutor, $tutor_data['direccion'], 1);
			}
			$imported = true;

			if ($imported) {
				$student['name']         = ucwords(strtolower($alumno->name_s));
				$student['surname']      = ucwords(strtolower($alumno->surname1_s));
				$student['lastname']     = ucwords(strtolower($alumno->surname2_s));
				$student['birthdate']    = $alumno->birthday;
				$student['age']          = $alumno->age;
				$student['genre']        = $alumno->sexo;
				$student['civil_stat']   = $alumno->edo_civil;
				$student['cellphone']    = $alumno->cellphone;
				$student['reference']    = $alumno->reference;
				$student['sickness']     = $alumno->sickness;
				$student['medication']   = $alumno->medication;
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

		if ($alumno['avatar'] === 'default.png') {
			$avatar = Config::get('AVATAR_DEFAULT_IMAGE_MALE');
			if ($alumno['genre'] === 'Femenino') {
				$avatar = Config::get('AVATAR_DEFAULT_IMAGE_FEMALE');
			}
		} else {
			$avatar = $alumno['avatar'];
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
