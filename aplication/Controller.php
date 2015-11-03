<?php
/**
 * Clase abstracta appController
 * declara variables protegidas view y db.
 * @package default
 */
abstract class appController{

	protected $_view;
	protected $db;
/**
 * __construct método que instancía las vistas y variables de conexión a la BD
 * @return type
 */
	public function __construct(){
		$this->_view = new View(new Request);
		$this->db = new ClassPDO();

	}
/**
 * index, método abstracto que redirecciona a las acciones.
 * @return type
 */
	abstract public function index();

	protected function redirect($url = array()){
		$path = "";
		if ($url["controller"]) {
			$path .= $url["controller"];
		}
		if ($url["action"]) {
				$path .= "/".$url["action"];		
		}
		header("LOCATION: ".APP_URL.$path);
	}
}