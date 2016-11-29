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
 * You create your database object and inject it to the classes.
 */
$mysqli = new mysqli('localhost', 'my_user', 'my_password', 'my_db');

/**
 * You create the object that will eventually execute the queries. 
 */
$usersTable = new UsersTable($mysqli);
$adminsTable = new AdminsTable($mysqli);
$widgetsTable = new WidgetsTable($mysqli);

/**
 * Now you can simply create the tables or drop them if needed 
 */
$usersTable->create();
$adminsTable->create();
$widgetsTable->create();

// if needed you can drop them as well, nothing fancy :-)
$widgetsTable->drop();

?>