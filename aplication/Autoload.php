<?php
/**
 * Función autoload
 * Carga los archivos de la carpeta "libs"
 * @param type $name 
 * @return type
 */
function __autoload($name){
	require_once ROOT."libs".DS.$name.".php";
}