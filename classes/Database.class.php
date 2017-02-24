<?php

/*
|--------------------------------------------------------------------------
| Database Class
|--------------------------------------------------------------------------
| This class works according to the singleton pattern
| It will give you only one connection at a time.
|
| @author Eden Reich
*/
class Database
{
	/**
	 * This property stores the instance of the connection.
	 *
	 * @var int
	 */
	protected static $instance = null;

	/**
	 * This property stores the database object(in that case mysqli).
	 *
	 * @var object
	 */
	protected $db;

	/**
	 * This property stores the query.
	 *
	 * @var string
	 */
	protected $query;

	/**
	 * The constructor will attempt to connect to the database.
	 *
	 * @param array | $credentials	
	 */
	private function __construct(Array $credentials)
	{
		$db = @mysqli_connect($credentials['host'], 
				      $credentials['username'], 
				      $credentials['password'], 
				      $credentials['name']);
		
		if(mysqli_connect_errno())
		{
			die('There was a problem connecting to the database. Error: ' . $db->error);
		}
		elseif(!$db->set_charset('utf8')) 
		{
			die('There was a problem at loading UTF-8 characters. Error: ' . $db->error);
		} 
		else 
		{
			$this->db = $db;
		}	
	}

	/**
	 * This method is just to make sure we are not already connected,
	 * if so it will give us the existing connection.
	 *
	 * @param array | $credentials
	 * @return instance
	 */
	public static function connect(Array $credentials)
	{
		if(static::$instance !== null)
		{
			return static::$instance;
		}

		return static::$instance = new static($credentials);
	}

	/**
	 * This method get the available connection.
	 *
	 * @return object
	 */
	public function getConnection()
	{
		return $this->db;
	}


	/**
	 * This method creates the table we want in the database.
	 *
	 * @param string | $table
	 * @param function | $callback
	 */
	public function create($tableName, Closure $callback = null)
	{
		$table = new TableBlueprint($tableName);

		if(is_callable($callback))
		{
			call_user_func_array($callback, [$table]);
		}

		if($this->notExists($tableName))
		{
			$sql = $table->buildQuery();
			$this->db->query($sql);
		}
	}

	/**
	 * This method simply drops the table we specify in the database.
	 *
	 * @param string | $table
	 */
	public function drop($tableName)
	{
		if($this->exists($tableName))
			return $this->db->query("DROP TABLE {$tableName};");
	}

	/**
	 * This method checks if  table is not exists.
	 *
	 * @param string | $tableName
	 * @return boolean
	 */
	protected function notExists($tableName)
	{
		if($result = $this->db->query("SHOW TABLES LIKE '{$tableName}'"))
		{
			if($result->num_rows >= 1)
				return false;		
		}

		return true;
	}

	/**
	 * This method checks if table is exist.
	 *
	 * @param string | $tableName
	 * @return boolean
	 */
	protected function exists($tableName)
	{
		if($result = $this->db->query("SHOW TABLES LIKE '{$tableName}'"))
		{
			if($result->num_rows >= 1)
				return true;		
		}

		return false;
	}

	/**
	 * This method looks up the database for a record
	 * by it's Id.
	 *
	 * @param integer | $id
	 * @param string | $tableName
	 *	
	 * @todo look up for the user with the matching $id in the database.
	 */
	public function find($id, $tableName)
	{
		
	}

	/**
	 * This method inserts the data to the database.
	 *
	 * @param integer | $id
	 * @param string | $tableName
	 *	
	 * @todo insert the data of that model to the specified table in the database.
	 */
	public function insert($data, $tableName)
	{

	}

	/**
	 * This method provide us 'Select [columns] from ' as for our query.
	 *
	 * @param array | $columns
	 *
	 * @return string 
	 */
	public function select($columns = array('*'))
	{
		$this->query = 'SELECT ' . implode(',', $columns) . ' ';

		return $this;
	}

	/**
	 * This method provide us the next part of the query.
	 *
	 * @param string | $tableName
	 * @return string 
	 */
	public function from($tableName)
	{
		$this->query .= 'FROM ' . $tableName . ' ';

		return $this;
	}

	/**
	 * This method fetchs the results.
	 *
	 * @return object 
	 */
	public function get()
	{
		if($stmt = $this->db->prepare($this->query))
		{
			$stmt->execute();

			$parameters = array();
			$results = array();
			
			$meta = $stmt->result_metadata();
			
			while($field = $meta->fetch_field())
			{
				$parameters[] = &$row[$field->name];
			}

			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			
			while($stmt->fetch())
			{
				$field = array();
				foreach($row as $name => $value)
				{
					$field[$name] = $value;
				}
				$results[] = $field;
			}

			$stmt->close();
		}

		return $results;
	}

	/**
	 * This method fetchs all the results from a specific table.
	 *
	 * @param string | $tableName
	 * 
	 * @return object 
	 */
	public function all($tableName) 
	{
		$allRecords = $this->select()->from($tableName)->get();
	}

}
