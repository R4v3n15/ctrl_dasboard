<?php

class MapaModel
{

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
