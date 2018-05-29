<?php

class WinesModel
{
	public static function getTiposWine(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$_sql = $database->prepare("SELECT * FROM wines.tipo_vino;");
		$_sql->execute();

		return $_sql->fetchAll();
	}

	public static function getCountries(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$_sql = $database->prepare("SELECT * FROM wines.paises;");
		$_sql->execute();

		return $_sql->fetchAll();
	}

	public static function getWineList(){
		$database = DatabaseFactory::getFactory()->getConnection();

		$_sql = $database->prepare("SELECT v.idVino, v.nombre as vino, vv.costo, v.price, tv.tipo, p.nombre as pais
									FROM wines.vinos_venta vv 
									INNER JOIN wines.vinos v ON vv.idVino = v.idVino
									INNER JOIN wines.tipo_vino tv ON tv.idTipoVino = v.idTipoVino
									LEFT JOIN wines.paises p ON v.idPais = p.idPais;");

		$_sql->execute();

		if ($_sql->rowCount() > 0) {
			// H::p($_sql->fetchAll());
			// exit();
			self::winesTable($_sql->fetchAll());
		}
	}

	public static function getWineTipos($tipo, $page){
		$database = DatabaseFactory::getFactory()->getConnection();

		$_sql = $database->prepare("SELECT v.idVino, v.nombre as vino, vv.costo, v.price, v.especs, v.updated, tv.tipo, p.nombre as pais
									FROM wines.vinos_venta vv 
									INNER JOIN wines.vinos v ON vv.idVino = v.idVino
									INNER JOIN wines.tipo_vino tv ON tv.idTipoVino = v.idTipoVino
									LEFT JOIN wines.paises p ON v.idPais = p.idPais
									WHERE tv.idTipoVino = :tipo;");

		$_sql->execute(array(':tipo' => $tipo));

		if ($_sql->rowCount() > 0) {
			H::getLibrary('paginadorLib');
	        $paginator = new \Paginador();
	        $filas     = 5;
	        $page      = (int)$page;
	        $counter = $page > 0 ? (($page*$filas)-$filas) + 1 : 1;
	        $items  = $paginator->paginar($_sql->fetchAll(), $page, $filas);
	        $paginacion = $paginator->getView('pagination_ajax', 'clases');
			self::winesTable($items);
			echo $paginacion;
		} else {
			echo '<h5 class="text-center text-secondary">No hay vinos...</h5>';
		}
	}

	public static function winesTable($wines){
        echo '<div class="table-responsive">';
	        echo '<table class="table table-striped table-sm table-bordered">';
		        echo '<thead>';
		            echo '<tr class="bg-secondary">';
		            echo '<th class="text-center">No.</th>';
		            echo '<th class="text-center">Vino</th>';
		            echo '<th class="text-center">Especs</th>';
		            echo '<th class="text-center">Price (v)</th>';
		            echo '<th class="text-center">Costo (v_v)</th>';
		            echo '<th class="text-center">Tipo</th>';
		            echo '<th class="text-center">Pais</th>';
		            echo '<th class="text-center">Actualizar</th>';
		            echo '</tr>';
		        echo '</thead>';
		        echo '<tbody>';
		        	$index = 1;
		            foreach ($wines as $wine) {
		            	$bg = '';
		            	if ($wine->updated) {
		            		$bg = 'bg-success';
		            	}
		                echo '<tr class="'.$bg.'">';
		                echo '<td class="text-center">'.$index++.'</td>'; 
		                echo '<td class="text-center">'.$wine->vino.'</td>';
		                echo '<td class="text-center">'.$wine->especs.'</td>'; 
		                echo '<td class="text-center" style="width: 100px;">'.
		                		'<input type="text" class="form-control form-control-sm" id="precio_'.$wine->idVino.'" value="'.$wine->price.'"></td>';
		                echo '<td class="text-center" style="width: 100px;">'.
		                		'<input type="text" class="form-control form-control-sm" id="costo_'.$wine->idVino.'" value="'.$wine->costo.'"></td>'; 
		                echo '<td class="text-center">'.$wine->tipo.'</td>'; 
		                echo '<td class="text-center">'.$wine->pais.'</td>'; 
		                echo '<td class="text-center">
		                        <button type="button"
		                                data-vino="'.$wine->idVino.'"
		                                class="btn btn-sm btn-warning btn_update mx-2"
		                                title="Edit"><i class="fa fa-edit"></i> </button>
		                      </td>'; 
		                echo '</tr>';   
		            }
		        echo '</tbody>';
	        echo '</table>';
        echo '</div>';
	}

	public static function createWine($name, $price, $cost, $type, $country, $especs){
		$database = DatabaseFactory::getFactory()->getConnection();
		if ($country == '' || $country == 0 || $country == null) {
			$country = NULL;
		}
        $commit   = true;

        $database->beginTransaction();
        try{

        	$_sql = $database->prepare("INSERT INTO wines.vinos(idRestaurante, nombre, idTipoVino, idPais, status, price, especs)
									        		VALUES(17, :name, :type, :country, 1, :price, :especs);");
        	$_sql->execute(array(':name' => $name,
					        	 ':type' => (int)$type,
					        	 ':country' => (int)$country,
				        		 ':price' => $price, 
				        		 ':especs' => $especs));
            
            if ($_sql->rowCount() > 0) {
            	$vino = $database->lastInsertId();
            	$qery = $database->prepare("INSERT INTO wines.vinos_venta(idVino, idRestaurante, idHotel, costo, status)
												            		VALUES(:vino, 17, 1, :costo, 1);");
            	$qery->execute(array(':vino' => $vino, ':costo' => $cost));

            	if ($qery->rowCount() > 0) {
            		$commit = true;
            	} else {
            		$commit = false;
            	}
                
            } else {
            	$commit = false;
            }
            
        }catch (PDOException $e) {
            $commit = false;
        }

        if (!$commit || !$updateAddress) {
            $database->rollBack();
            return array('succcess' => false);
        }else {
            $database->commit();
            return array('succcess' => true);
        }
	}

	public static function updateWine($wine, $price, $cost){
		$database = DatabaseFactory::getFactory()->getConnection();

		$_sql = $database->prepare("UPDATE wines.vinos SET price = :price, updated=1 WHERE idVino = :vino;");
		$updated = $_sql->execute(array(':price' => $price, ':vino' => $wine));

		if ($updated) {
			$qry = $database->prepare("UPDATE wines.vinos_venta SET costo = :costo WHERE idVino = :vino;");
			$saved = $qry->execute(array(':costo' => $cost, ':vino' => $wine));

			return array('succcess' => $saved, 'message' => 'Actualizacion en vinos venta');
		}
		return array('succcess' => false, 'message' => 'Error al actualizar');
	}

}
