<?php


class WidgetsTable extends Migrate
{

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

	public function drop()
	{
		$this->db->drop('widgets');
	}

}