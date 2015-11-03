<?php
/**
 * Clase PDO
 * Declara las variables de conexión a la BD
 * @package default
 */
class ClassPDO
{
	public $connection;
	private $dsn;
	private $drive;
	private $host;
	private $database;
	private $username;
	private $password;
	public $result;
	public $lastInsertId;
	public $numberRows;

/**
 * Constructor de la clase PDO
 * Inicializa las variables de conexión
 * @param type $drive 
 * @param type $host 
 * @param type $database 
 * @param type $username 
 * @param type $password 
 * @return void
 */
	public function __construct($drive = 'mysql', $host = 'localhost', $database = 'gestion', $username = 'root', $password = 'root')
	{
		$this->drive = $drive;
		$this->host = $host;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
		$this->connection();
	}

	/**
	 * Método connection
	 * Realiza la conexión a la BD
	 * @return type
	 */
	private function connection(){
		$this->dsn = $this->drive.':host='.$this->host.';dbname='.$this->database;

		try{

			$this->connection = new PDO(

				$this->dsn,
				$this->username,
				$this->password

				);
			$this->connection->setAttribute(

				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION

				);

		}catch(PDOException $e){

			echo "ERROR: ".$e->getMessage();
			die();
		}
	}
/**
 * Método find
 * Busca los registros en la BD
 * @param type $table 
 * @param type $query 
 * @param type $options 
 * @return type
 */
	public function find($table = null, $query = null, $options = array()){

		$fields = '*';

		$parameters = '';

		if (!empty($options['fields'])) {
			$fields = $options['fields'];
		}

		if (!empty($options['conditions'])) {
			$parameters = '	WHERE '.$options['conditions'];
		}

		if (!empty($options['group'])) {
			$parameters .= '	GROUP BY '.$options['group'];
		}

		if (!empty($options['order'])) {
			$parameters .= '	ORDER BY '.$options['order'];
		}

		if (!empty($options['limit'])) {
			$parameters .= '	LIMIT '.$options['limit'];
		}

		switch ($query) {
			case 'all':
				$sql = "SELECT $fields FROM $table".' '.$parameters;
				$this->result = $this->connection->query($sql);
				break;

			case 'count':
				$sql = "SELECT COUNT(*) FROM $table".' '.$parameters;
				$result = $this->connection->query($sql);
				$this->result = $result->fetchColumn();
				break;

			case 'first':
				$sql = "SELECT $fields FROM $table".' '.$parameters;
				$result = $this->connection->query($sql);
				$this->result = $result->fetch();
				break;
			
			default:
				$sql = "SELECT $fields FROM $table ".' '.$parameters;
				$this->result = $this->connection->query($sql);
				break;
		}
				return $this->result;
	}

/**
 * Método Save
 * Inserta nuevos registros a la BD
 * @param type $table 
 * @param type $data 
 * @return type
 */
	public function save($table = null, $data = array()){

		$sql = "SELECT * FROM $table";
		$result = $this->connection->query($sql);

		for ($i=0; $i < $result->columnCount(); $i++) { 
			$meta=$result->getColumnMeta($i);
			$fields[$meta['name']] = null;
		}

		$fieldsToSave = 'id';
		$valueToSave = 'NULL';

		foreach ($data as $key => $value) 
		{
			if (array_key_exists($key, $fields)) {
				$fieldsToSave .=', '.$key;
				$valueToSave .=', '."\"$value\"";
			}
		}

		$sql = "INSERT INTO $table ($fieldsToSave)VALUES($valueToSave);";

		//echo $sql;
		//exit;

		$this->result = $this->connection->query($sql);

		return $this->result;
	}

/**
 * Método Update
 * Actualiza los cambios en los registros de la BD
 * @param type $table 
 * @param type $data 
 * @return type
 */
	public function update($table = null, $data = array()){

		$sql = "SELECT * FROM $table";
		$result = $this->connection->query($sql);

		for ($i=0; $i < $result->columnCount(); $i++) { 
			$meta=$result->getColumnMeta($i);
			$fields[$meta['name']] = null;
		}

		if (array_key_exists("id", $data)) {
			$fieldsToSave = "";
			$id = $data["id"];
			unset($data["id"]);

			$i = 0;
			foreach ($data as $key => $value) {
				if (array_key_exists($key, $fields)) {
					$fieldsToSave .=$key."="."\"$value\", ";
				}
			}
			$fieldsToSave = substr_replace($fieldsToSave, '', -2);

			$sql = "UPDATE $table SET $fieldsToSave WHERE $table.id=$id;";
			//echo $sql;
			//exit;

		}

		$this->result = $this->connection->query($sql);
		return $this->result;

	}

/**
 * Método Delete
 * Elimina registros de la BD
 * @param type $table 
 * @param type $condition 
 * @return type
 */
	public function delete($table = null, $condition = null){

		$sql = "DELETE FROM $table WHERE $condition".";";
		
		$this->result = $this->connection->query($sql);

		$this->numberRows = $this->result->rowCount();

		return $this->result;
			
	}
}

$db = new ClassPDO();
