<?php

// URL
function _root(){
    return Config::get('URL');
}
// Avatars folde
function _avatar(){
    return Config::get('URL').Config::get('PATH_AVATARS_PUBLIC');
}

function _public(){
    return Config::get('URL').Config::get('PATH_PUBLIC');
}

// Active view controller
function _active($filename, $action){
	return View::active($filename, $action) ? 'active' : '';
}