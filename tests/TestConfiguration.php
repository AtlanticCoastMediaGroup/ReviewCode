<?php
class TestConfiguration
{
	/**
	* Sets the environment up for testing
	*/
	static function setUp()
	{
		
		// Use Autoload so that we don't have to include/require every class
		//require_once "Zend/Loader.php";
		//Zend_Loader::registerAutoload();
		require_once 'Zend/Loader/Autoloader.php';
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('App_');
	}

	static function setUpDatabase()
	{
		require '../application/bootstrap.php';

		$db = Zend_Registry::get('configuration')->database->params->dbname;

		// delete any pre-existing databases
		if(file_exists($db)) unlink($db);

		// run the database set up script to recreate the database
		//require '../scripts/load.sqlite.php';
	}
}
TestConfiguration::setUp();
