<?php

/*
|--------------------------------------------------------------------------
| WidgetsTable Class
|--------------------------------------------------------------------------
| this class creates or drop the widgets table
| 
*/
class WidgetsTable extends Migrate
{
	/**
	 * Here you create the table
	 */
	public function create()
	{
		$this->db->create('widgets', function(TableBlueprint $table)
		{
			$table->integer('id', 11, 'AUTO_INCREMENT');
			$table->string('title', 255, 'NOT NULL');
			$table->string('content', 255, 'NOT NULL');
			$table->primary('id');
		});
	}

	/**
	 * Here you drop the table
	 */
	public function drop()
	{
		$this->db->drop('widgets');
	}

}