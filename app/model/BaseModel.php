<?php
namespace App\Model;
use PDO;

abstract class BaseModel {
    
    private static $hostname = 'localhost';
    private static $username = 'root';
    private static $password = '';
    private static $databaseName = 'mis';
    private static $dsn = '';
    private $connection = null;
    private $statement = null;
	private $sql = '';

	abstract protected function getFillable();
	abstract protected function getTable();
	abstract protected function getOrderBy();

    protected function connection() {
        try{
            self::$dsn = 'mysql:host='.self::$hostname.';dbname='.self::$databaseName;
            $this->connection = new PDO(self::$dsn, self::$username, self::$password);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $this->connection;
        }
        catch(\PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
    }

	public function getHostName() {
		return self::$hostname;
	}

	public function getUserName() {
		return self::$username;
	}

	public function getPassword() {
		return self::$password;
	}

	public function getDatabaseName() {
		return self::$databaseName;
	}

	public function all() {
		try {
			$this->sql = 'SELECT * FROM '.$this->getTable().' WHERE deleted_at IS NULL ORDER BY '.$this->getOrderBy()[0].' '.$this->getOrderBy()[1];
			$this->statement  = $this->connection()->prepare($this->sql);
			$this->statement ->execute();

			return $this->statement->fetchAll();
		}
		catch(\PDOException $exception){
            echo "Model error: " . $exception->getMessage();
        }
	}

	public function selects($show_fields = [], $join_tables = [], $where_fields = []) {
		$fields = $this->fieldBuilder($show_fields);
		$orders = $this->orderBuilder();
		$joins = $this->joinBuilder($join_tables);
		$wheres = $this->whereBuilder($where_fields);
		
		$this->sql = "SELECT ".$fields." FROM ".$this->getTable()." ".$joins." WHERE ".$this->getTable().".deleted_at IS NULL ".$wheres.' ORDER BY '.$this->getOrderBy()[0].' '.$this->getOrderBy()[1];
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindWhereBuilder($this->statement, $where_fields);
		$this->statement ->execute();

		return $this->statement->fetchAll();
	}

	public function selectsAdvanced($show_fields = [], $join_tables = [], $where_fields = [], $order_fields = []) {
		$fields = $this->fieldBuilder($show_fields);
		$orders = $this->orderAdvancedBuilder($order_fields);
		$joins = $this->joinBuilder($join_tables);
		$wheres = $this->whereBuilder($where_fields) ;

		$this->sql = "SELECT ".$fields." FROM ".$this->getTable()." ".$joins." WHERE ".$this->getTable().".deleted_at IS NULL ".$wheres.' ORDER BY '.$orders;
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindWhereBuilder($this->statement, $where_fields) ;
		$this->statement ->execute();

		return $this->statement->fetchAll();
	}

	public function select($show_fields = [], $join_tables = [], $where_fields = []) {
		$fields = $this->fieldBuilder($show_fields);
		$wheres = $this->whereBuilder($where_fields);
		$joins = $this->joinBuilder($join_tables);
		
		$this->sql = "SELECT ".$fields." FROM ".$this->getTable()." ".$joins." WHERE ".$this->getTable().".deleted_at IS NULL ".$wheres;
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindWhereBuilder($this->statement, $where_fields);
		$this->statement ->execute();

		return $this->statement->fetch();
	}

	public function search($show_fields = [], $join_tables = [], $data) {
		$shows = $this->fieldBuilder($show_fields);
		$orders = $this->orderBuilder();
		$joins = $this->joinBuilder($join_tables);
		$searches = $this->searchBuilder(array_keys($data));
		
		$this->sql = 'SELECT '.$shows.' FROM '.$this->getTable().' '.$joins.' WHERE '.$this->getTable().'.deleted_at IS NULL AND '.$this->getTable().'.status IN ("Active", "Default") '.$searches.' '.$orders;
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindSearchBuilder($this->statement, $data);
		$this->statement ->execute();

		return $this->statement->fetchAll();
	}

	public function searchAdvanced($show_fields = [], $join_tables = [], $data) {
		$shows = $this->fieldBuilder($show_fields);
		$orders = $this->orderBuilder();
		$joins = $this->joinBuilder($join_tables);
		$searches = $this->searchAdvancedBuilder($data);

		$this->sql = 'SELECT '.$shows.' FROM '.$this->getTable().' '.$joins.' WHERE '.$this->getTable().'.deleted_at IS NULL AND '.$this->getTable().'.status IN ("Active", "Default") '.$searches.' '.$orders;
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindSearchAdvancedBuilder($this->statement, $data);
		$this->statement ->execute();

		return $this->statement->fetchAll();
	}

	public function rowsCount( $join_tables = [], $where_fields = []) {
		$wheres = $this->whereBuilder($where_fields);
		$joins = $this->joinBuilder($join_tables);

		$this->sql = "SELECT count(".$this->getTable().".id) FROM ".$this->getTable()." ".$joins." WHERE ".$this->getTable().".deleted_at IS NULL ".$wheres;
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindWhereBuilder($this->statement, $where_fields);
		$this->statement ->execute();

		return $this->statement ->fetchColumn();
	}

	public function store($data) {
        try {
			$fields = $this->fieldBuilder(array_keys($data));
			$parameters = $this->parameterBuilder(array_keys($data));

			$this->sql = 'INSERT INTO '.$this->getTable().' ('.$fields.') VALUES ('.$parameters.')';
			$this->statement = $this->connection()->prepare($this->sql);
			$this->statement = $this->bindBuilder($this->statement, $data);

            return $this->statement->execute() ? true : false;
		}
		catch(\PDOException $exception){
            echo "Model error: " . $exception->getMessage();
        }
    }

	public function lastInsertId($data) {
		try {
			$fields = $this->fieldBuilder(array_keys($data));
			$parameters = $this->parameterBuilder(array_keys($data));

			$this->sql = 'INSERT INTO '.$this->getTable().' ('.$fields.') VALUES ('.$parameters.')';
			$connection =  $this->connection();
			$this->statement = $connection->prepare($this->sql);
			$this->statement = $this->bindBuilder($this->statement, $data);
            $this->statement->execute();

			return $connection->lastInsertId();
		}
		catch(\PDOException $exception){
            echo "Model error: " . $exception->getMessage();
        }
	}

	public function show($data) {
		try {
			$this->sql = 'SELECT * FROM '.$this->getTable().' WHERE id=:id';
			$this->statement  = $this->connection()->prepare($this->sql);
			$this->statement = $this->bindBuilder($this->statement, $data);
			$this->statement ->execute();

			return $this->statement->fetch();
		}
		catch(\PDOException $exception){
            echo "Model error: " . $exception->getMessage();
        }
	}

	public function update($data) {
		try {
			$set_data = $data;
			unset($set_data['id']);

			$fields = $this->setFieldBuilder(array_keys($set_data));
			$sort_data = $this->sortFieldLast($data, 'id');

			$this->sql = 'UPDATE '.$this->getTable().' SET '.$fields.' WHERE id=:id';
			$this->statement = $this->connection()->prepare($this->sql);
			$this->statement = $this->bindBuilder($this->statement, $sort_data);

			return $this->statement->execute() ? true : false;
		}
		catch (\PDOException $exception) {
			echo "Model error: " . $exception->getMessage();
		}
	}

	public function remove($data) {
		try {
			$set_data = $data;
			unset($set_data['id']);

			$fields = $this->setFieldBuilder(array_keys($set_data));
			$sort_data = $this->sortFieldLast($data, 'id');

			$this->sql = 'UPDATE '.$this->getTable().' SET '.$fields.' WHERE id=:id';
			$this->statement = $this->connection()->prepare($this->sql);
			$this->statement = $this->bindBuilder($this->statement, $sort_data);

			return $this->statement->execute() ? true : false;
		}
		catch (\PDOException $exception) {
			echo "Model error: " . $exception->getMessage();
		}
	}

	public function delete($where_fields = []) {
		$wheres = $this->whereBuilder($where_fields);
		
		$this->sql = 'DELETE FROM '.$this->getTable().' WHERE '.$this->getTable().'.id IS NOT NULL  '.$wheres;
		$this->statement  = $this->connection()->prepare($this->sql);
		$this->statement = $this->bindWhereBuilder($this->statement, $where_fields);
		return $this->statement->execute() ? true : false;
	}

	
	// =======================================================
	// SQL QUERY BUILDER HELPER
	// =======================================================
	protected function getFields($tableName) {
		try {
			$columns = [];
			self::$sql = 'SHOW COLUMNS FROM '.$tableName;
			self::$statement = self::connection()->prepare(self::$sql);
			self::$statement->execute();
			$columsData = self::$statement->fetchAll(PDO::FETCH_COLUMN);
			return $columsData;
		}
		catch(\PDOException $exception){
            echo "Query helper error: " . $exception->getMessage();
        }
	}

	function searchBuilder($fields) {
		if(count($fields) > 0) {
			return 'AND '.implode(' OR ', array_map(function($field){ return $field.' LIKE :'.$field; }, $fields));
		}
		return '';
	}

	function searchAdvancedBuilder($filters) {

		if(count($filters) > 0) {
			$searches = [];

			foreach ($filters as $index => $filter) {
				$searches[] = $filter['column']." LIKE :".$filter['parameter'];
			}	

			return 'AND '.implode(' OR ', $searches);
		}
		return '';
	}

	function orderBuilder() {
		if (count($this->getOrderBy()) == 2 ) {
			return $this->order_fields = '  ORDER BY '.$this->getOrderBy()[0].' '.$this->getOrderBy()[1];
		}
		return '';
	}

	function orderAdvancedBuilder($fields) {
		$orders = [];

		foreach ($fields as $index => $rows) {
			if(count($rows) == 3) {
				array_push($orders, $rows['table'].".".$rows['key']." ".$rows['value']);
			}
		}

		if(count($fields) > 0) {
			return implode(" , ", $orders);
		}
		
		return "";
	}

	function joinBuilder($tables) {
		$joins = [];
		
		foreach($tables as $index => $table) {
			if(count($table) == 4) {
				$sql = " ".$table[0]." JOIN ".$table[1]." ON ".$table[3]."= ".$table[2];
				array_push($joins, $sql);
			}
		}

		return join(" ", $joins);
	}

	protected function whereBuilder($parameters) {
		$wheres = [];

		foreach ($parameters as $index => $rows) {
			if(count($rows) == 3) {
				$table = $rows['table'];
				$key = $rows['key'];
				array_push($wheres, $table.".".$key." = :".$key);
			}
		}

		if(count($wheres) > 0) {
			return " AND ".implode(" AND ", $wheres);
		}
		return "";
	}

	protected function whereOrBuilder($parameters) {
		$wheres = [];

		foreach ($parameters as $index => $row) {
			if(count($row) == 4) {
				$wheres[] = $row['table'].".".$row['column']." = :".$row['parameter'];

			}
		}

		if(count($wheres) > 0) {
			return " AND ".implode(" OR ", $wheres);
		}
		return "";
	}

    protected function fieldBuilder($fields) {
		return join(', ', $fields);
	}

	protected function parameterBuilder($parameters) {
		return implode(', ', array_map(function($field){ return sprintf(':%s ', $field);}, $parameters));
	}

	protected function setFieldBuilder($parameters) {
		return implode(', ', array_map(function($field){ return $field.'=:'.$field;}, $parameters));
	}

	// PREPARED STATENENT BINDING BUILDER
	protected function bindSearchBuilder($statement, $data) {
		$fields = array_keys($data);
		$values = array_values($data);

		foreach ($fields as $index => $field) {
			$statement->bindValue(':'.$field, '%'.$values[$index].'%');
		}

		return $statement;
	}

	protected function bindSearchAdvancedBuilder($statement, $filters) {
		foreach ($filters as $index => $filter) {
			$statement->bindValue(':'.$filter['parameter'], '%'.$filter['value'].'%');
		}

		return $statement;
	}

	protected function bindWhereBuilder($statement, $data) {
		foreach ($data as $index => $rows) {
			if(count($rows) == 3) {
				$table = $rows['table'];
				$key = $rows['key'];
				$value = $rows['value'];

				$statement->bindValue(":".$key, $value);
			}
		}

		return $statement;
	}

	protected function bindWhereAdvancedBuilder($statement, $wheres) {
		foreach ($wheres as $index => $where) {
			$statement->bindValue(':'.$where['parameter'], '%'.$where['value'].'%');
		}

		return $statement;
	}


    protected function bindBuilder($statement, $data) {
		$fields = array_keys($data);
		$values = array_values($data);

		foreach ($fields as $index => $field) {
			if(is_array($values[$index])) {
				foreach ($values[$index] as $index => $value) {
					$statement->bindValue(':'.$field.$index, $value);
				}
			}
			else {
				$statement->bindValue(':'.$field, $values[$index]);
			}
		}

		return $statement;
	}
	// END PREPARED STATENENT BINDING BUILDER

	public static function sortFieldLast($data, $field) {
		$lastField = $data[$field];
		unset($data[$field]);
		$data[$field] = $lastField;

		return $data;
	}

	protected static function isUnique($tableName, $rules) {
		try {
			self::$sql = 'SELECT id FROM '.$tableName.' WHERE '.$rules['field'].'=:'.$rules['field'];
			self::$statement = self::connection()->prepare(self::$sql);
			self::$statement->bindValue(':'.$rules['field'], $rules['value']);
			self::$statement->execute();
			return (self::$statement->rowCount() == 0) ? true : false;
		}
		catch(\PDOException $exception){
            echo "Query helper error: " . $exception->getMessage();
        }
	}

	protected static function isDataExist($tableName, $data) {
		try {
			self::$sql = 'SELECT '.$data['field'].' FROM '.$tableName.' WHERE '.$data['field'].'=:'.$data['field'];
			self::$statement = self::connection()->prepare(self::$sql);
			self::$statement->bindValue(':'.$data['field'], $data['value']);
			self::$statement->execute();
			return (self::$statement->rowCount() > 0) ? true : false;
		}
		catch(\PDOException $exception){
            echo "Query helper error: " . $exception->getMessage();
        }
	}
}
?>