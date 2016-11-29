<?php

/*
|--------------------------------------------------------------------------
| Migrate Class
|--------------------------------------------------------------------------
| Pretty empty right now, just to make the table classes look a bit cleaner,
| I can add here some more logic perhaps later.
| 
*/
abstract class Migrate
{
	protected $db;

	public function __construct(Database $db)
	{
		$this->db = $db;
	}

}