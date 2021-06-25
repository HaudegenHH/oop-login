<?php 

//singleton pattern -> main static method getInstance

class DB {

	// the underscores jst indicate that they are private props
	private static $_instance = null;
	private $_pdo, $_query, $_error = false, $_results, $_count = 0;

	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host='. Config::get('mysql/host') . ';dbname=' . Config::get('mysql/dbname'), Config::get('mysql/username'), Config::get('mysql/password'));
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		// first looks if the db instance was set before 
		// if not it will create one and put it into the static $_instance
		// 2nd time the instance will be returned right away without
		// creating a new instance
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}		
		return self::$_instance;
	}

	public function query($sql, $params = []) {
		// reset error first cause we re going to have multiple querys
		$this->_error = false;

		// prevent sql injection
		if($this->_query = $this->_pdo->prepare($sql)) {
			//echo "success";
			$x = 1;
			if(count($params)) {
				foreach($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if($this->_query->execute()) {
				//echo 'success';
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		// return the current object for method chaining	
		return $this;
	}

	public function action($action, $table, $where = []) {

		if(count($where) === 3) {
			$operators = ['=','>','<','<=','>='];

			$field    = $where[0];
			$operator = $where[1];
			$value    = $where[2];

			// is the operator in the operators array?
			if(in_array($operator, $operators)) {
				// then we can construct the query
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if(!$this->query($sql, [$value])->error()) {
					return $this;
				}
			}
		}

		return false;
	}

	public function get($table, $where) {
		return $this->action('SELECT *',$table,$where);
	}

	public function insert($table, $fields = []) {

		$keys = array_keys($fields);
		$values = '';
		$x = 1; 

		// adding a ? to the values string for each field
		foreach($fields as $field) {
			$values .= '?';
			// as long its not the last add a comma and a space 
			if($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}

		//die($values);

		// separator when imploding $keys: `, `
		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

		//die($sql);

		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		
		return false;
	}

	public function update($table, $id, $fields) {
		$set = '';
		$x = 1;
		// building up the "set" string for update:
		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			// again adding comma and space unless its the last field
			if($x < count($fields)) {
				$set .= ', '; 
			}

			$x++;
		}

		//die($set);

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}

	public function delete($table, $where) {
		return $this->action('DELETE *', $table, $where);
	}

	public function results() {
		return $this->_results;
	}

	public function first() {
		return $this->results()[0];
	}

	public function error() {
		return $this->_error;
	}

	public function count() {
		return $this->_count;
	}
}
