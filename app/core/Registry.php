<?php

// Class Registry: Allows to set and get data dinamically
class Registry {

	private static $data = array();
	/**
     * gets/returns the value of a specific key
     */
	public static function get($key, $default = null) {
		// var_dump(Config::get("production"));
		if(isset(static::$data[$key])) {
			if($key=='css'){
				foreach(static::$data[$key] as $cadena){
					// $ext = ".css";
					$css=explode('&',$cadena);
					
					$pathCss = Config::get("PATH_PUBLIC") . $css[1]."/".$css[0] . ".min.css";

					if(Environment::get() === "production" && is_readable($pathCss)){
			        //     require_once $libraryPath;
						$ext = ".min.css";
			        }else {
			        	// var_dump(is_readable(Config::get("PATH_PUBLIC") . $css[1]."/".$css[0] . ".css"));
						if(is_readable(Config::get("PATH_PUBLIC") . $css[1]."/".$css[0] . ".css")){ //just quick fix for motion-ui in calendar/ageanda
							$ext = ".css";
						}else {
							$ext = ".min.css";
						}
			        }
						
						echo "<link rel='stylesheet' href='".Config::get('URL').$css[1]."/".$css[0]. $ext ."' />";
				}
			}
			if($key=='js'){
				// $ext = ".js";
				foreach(static::$data[$key] as $cadena){
					$js=explode('&',$cadena);
					
					$pathJs = Config::get("PATH_PUBLIC") . $js[1] ."/". $js[0] . ".min.js";

					if(Environment::get() === "production" && is_readable($pathJs)){
			        //     require_once $libraryPath;
						$ext = ".min.js";
			        }else {
						if(is_readable(Config::get("PATH_PUBLIC") . $js[1] ."/". $js[0] . ".js")){ //just quick fix for motion-ui in calendar/ageanda
							$ext = ".js";
						}else {
							$ext = ".js";
						}
			        }
			        
					echo "<script type='text/javascript' src='".Config::get('URL').$js[1]."/".$js[0]. $ext ."'></script>";
				}
			}
			if($key=='navBCs'){
				$cadena = static::$data[$key];
				for ($i=0; $i < count($cadena) ; $i++) { 
					$navredirect=explode('#', $cadena[$i]);
					for ($x=0; $x < count($navredirect) ; $x++) {
						if ($navredirect[$x] == 'show-for-sr') {
							$data = '<span class="show-for-sr">Current: </span>' . $navredirect[$x-1]  ;
						}else {
							switch ($i) {
								case 1:
									$var = $cadena[$i];
									break;
								case 2:
									$var = $cadena[$i-1] . '/' . $cadena[$i];
									break;
								default:
									break;
							}
							$data = '<a href="'.Config::get('URL'). (isset($var) && $var !== "" ? $var : "" ).'"> '. $navredirect[$x] .'  </a>';
						}
					}
					echo ' <li>' . $data . '</li>';
				}
			}
			return static::$data[$key];
		}
		return $default;
	}

	/**
	 * Set  the value of a specific key in objects
	 *
	 * @param mixed $key key
	 * @return mixed the key's value or nothing
	 */
	public static function prop($object, $key, $default = null) {
		if($obj = static::get($object)) {
			return $obj->{$key};
		}
		return $default;
	}


	/**
	 * Set  the value of a specific key
	 *
	 * @param mixed $key key
	 * @return mixed the key's value or nothing
	 */
	public static function set($key, $value) {
		static::$data[$key] = $value;
	}

	/**
     * Returns .
     *
     * @param mixed $key key
     * @return bool $key key
     */
	public static function has($key) {
		return isset(static::$data[$key]);
	}
}
