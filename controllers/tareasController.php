<?php
/**
 * 
 * @package default
 */
class tareasController extends appController
{

	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->_view->titulo = "Página Principal";
		$this->_view->tareas = $this->db->find('tareas', 'all', null);
		$this->_view->renderizar('index');

	}

	public function add(){
		if ($_POST) {
			if ($this->db->save('tareas', $_POST)) {
				$this->redirect(array('controller'=>'tareas'));
			}else{
				$this->redirect(array('controller'=>'tareas', 'action'=>'add'));
			}
		}else{
			$this->_view->titulo = "Agregar Tarea";
		}

		$this->_view->renderizar('add');
	}

	public function edit($id = null){
		if ($_POST) {
			if ($this->db->update('tareas', $_POST)) {
				$this->redirect(
					array('controller'=>'tareas', 'action'=>'index')
					);
			}else{
				$this->redirect(
					array('controller'=>'tareas', 'action'=>'edit/'.$_POST['id'])
					);
			}
			
		}else{
			$this->_view->titulo = "Editar Tarea";
			$this->_view->tarea = $this->db->find('tareas', 'first', array('conditions'=>'id='.$id));
			$this->_view->renderizar('edit');
		}

	}

	public function delete ($id = null){
		$conditions = 'id='.$id;
		if ($this->db->delete('tareas', $conditions)) {
			$this->redirect(
					array('controller'=>'tareas', 'action'=>'index')
					);	
		}

	}
}