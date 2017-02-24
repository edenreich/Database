<?php

/*
|--------------------------------------------------------------------------
| TableBlueprint Class
|--------------------------------------------------------------------------
| This Class is responsible for the table pattern, with it you create the
| Tables for the database.
| Inspired from Laravel Framework, created it for my own perposes. So it looks very similar only
| here obviously there is less functionallity.
| There is probably a bit of redundancy code and it could be done better so feel free to make any suggestions.
| 
| @autor Eden Reich
*/
class TableBlueprint
{
	/**
	 * Stores all the columns.	
	 *
	 * @var array
	 */
	protected $columns = [];
	
	/**
	 * The table name.	
	 *
	 * @var string
	 */
	protected $name = '';
	
	/**
	 * The primary key of the to be created table.	
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	
	/**
	 * We setting the $tableName and the logger	
	 *
	 * @param string | $tableName
	 */
	public function __construct($tableName)
	{
		$this->name = $tableName;
	}

	/**
	 * This method saves a column from type of a string.
	 *
	 * @param string | $columnName
	 * @param integer | $charsCount
	 * @param mixed | $extra
	 */
	public function string($columnName, $charsCount = 255, $extra = null)
	{
		$this->columns[$columnName] = [
				'name' => $columnName,
				'size' => $charsCount,
				'type' => 'varchar',
		];

		$this->set($columnName, $extra);
	}

	/**
	 * This method saves a column from type of an integer.
	 *
	 * @param string | $columnName
	 * @param integer | $charsCount
	 * @param mixed | $extra
	 */
	public function integer($columnName, $charsCount = 11, $extra = null)
	{
		$this->columns[$columnName] = [
				'name' => $columnName,
				'size' => $charsCount,
				'type' => 'int',
		];

		$this->set($columnName, $extra);
	}

	/**
	 * This method sets the column to be as the primary key.
	 *
	 * @param string | $columnName
	 */
	public function primary($columnName)
	{
		if(array_key_exists($columnName, $this->columns))
			return $this->primaryKey = $columnName;
		
		throw new Exception('The column `' . $columnName . '` must be created first.');
	}

	/**
	 * This method sets the extra rules for the fields.
	 *
	 * @param string | $columnName
	 * @param string | $extra
	 */
	protected function set($columnName, $extrasOrExtra)
	{
		if($extrasOrExtra == NULL)
			return;

		if(is_array($extrasOrExtra))
		{
			foreach($extrasOrExtra as $key => $extra)
			{
				$extra = strtoupper($extra);
				switch($extra)
				{
					case 'AUTO_INCREMENT':
						$this->columns[$columnName]['autoIncrement'] = $extra;
					break;
					case 'UNSIGNED':
						$this->columns[$columnName]['unsigned'] = $extra;
					break;
					case 'NOT NULL':
						$this->columns[$columnName]['notNull'] = $extra;
					break;
					case 'DEFAULT':
						$this->columns[$columnName]['default'] = "{$extra} {$key}";
					break;
				}
			}
		} 
		else if(is_string($extrasOrExtra))
		{
			$extra = strtoupper($extrasOrExtra);
			switch($extra)
			{
				case 'AUTO_INCREMENT':
					$this->columns[$columnName]['autoIncrement'] = $extrasOrExtra;
				break;
				case 'UNSIGNED':
					$this->columns[$columnName]['unsigned'] = $extrasOrExtra;
				break;
				case 'NOT NULL':
					$this->columns[$columnName]['notNull'] = $extrasOrExtra;
				break;
				case 'DEFAULT':
					$this->columns[$columnName]['default'] = $extrasOrExtra;
				break;
			}
		} 
	}

	/**
	 * A Getter for the table name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * A Getter for the columns.
	 *
	 * @param array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * This method builds the query for executing.
	 *
	 * @return string
	 */
	public function buildQuery() 
	{
		$query = "CREATE TABLE IF NOT EXISTS `{$this->name}`";		
		$query .= "(";

		foreach($this->columns as $column => $options)
		{
			$query .= "`{$column}` {$options['type']}({$options['size']})";
			
			if(isset($options['unsigned']))
			{
				$query .= " {$options['unsigned']}";
			}

			if(isset($options['default']))
			{
				$query .= " {$options['default']}";
			}

			if(isset($options['notNull']))
			{
				$query .= " {$options['notNull']}";
			}

			if(isset($options['autoIncrement']))
			{
				$query .= " {$options['autoIncrement']}";
			}

			if($column !== end($this->columns)['name'])
				$query .= ", ";

		}

		if($this->primaryKey) 
		{
			$query .= ", PRIMARY KEY (`{$this->primaryKey}`) ";
		}

		$query .= ");";

		return $query;
	}

	/**
	 * This method saves column from type of a Boolean.
	 *
	 * @param string | $columnName
	 * @param string | $default
	 */
	public function boolean($columnName, $default = 0)
	{
		$this->columns[$columnName] = [
				'name' => $columnName,
				'size' => 1,
				'type' => 'tinyint',
		];

		$this->set($columnName, $default);
	}
}
