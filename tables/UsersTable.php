<?php

/*
|--------------------------------------------------------------------------
| UsersTable Class
|--------------------------------------------------------------------------
| this class creates or drop the users table
| 
*/
class UsersTable extends Migrate
{
	/**
	 * Here you create the table
	 */
	public function create()
	{
		$this->db->create('users', function(TableBlueprint $table) 
		{
			$table->integer('id', 11, ['AUTO_INCREMENT','UNSIGNED']);
			$table->string('name', 255, 'NOT NULL');
			$table->string('email', 255, 'NOT NULL');
			$table->string('password', 255, 'NOT NULL');

			$table->primary('id');
		});
	}

	/**
	 * Here you drop the table
	 */
	public function drop()
	{
		$this->db->drop('users');
	}
}