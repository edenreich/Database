<?php

/*
|--------------------------------------------------------------------------
| Init File
|--------------------------------------------------------------------------
| the logic here you would probably want to do in an init file.
| 
|
*/

/**
 * for simplicity I use here spl_autoload_register, you could choose to use psr autoload  
 * with composer
 */
spl_autoload_register(function($class) 
{
	if(file_exists('classes/' . $class  .'.class.php'))
		require_once 'classes/' . $class  .'.class.php';

	else if(file_exists('tables/' . $class  .'.php'))
		require_once 'tables/' . $class  .'.php';
});



/**
 * creating the database object and inject it to the classes.
 */
$credentials = array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'name' => 'my_db'
	       );

$connection = Database::connect($credentials);

/**
 * creating the objects that will eventually execute the queries. 
 */
$usersTable = new UsersTable($connection);
$adminsTable = new AdminsTable($connection);
$widgetsTable = new WidgetsTable($connection);

/**
 * Now you can simply create the tables or drop them if needed 
 */
// $usersTable->create();
// $adminsTable->create();
$widgetsTable->create();

// if needed you can drop them as well, nothing fancy :-)
// $widgetsTable->drop();

?>
