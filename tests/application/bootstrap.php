<?php
error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
define('APPLICATION_PATH', BASE_PATH . '/application');
defined('APPLICATION_URL')
    || define('APPLICATION_URL', 'http://localhost/zf-project/zf_cms');
// Include path
set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/library'
    . PATH_SEPARATOR . get_include_path()
);

// Define application environment
define('APPLICATION_ENV', 'testing');

/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
require_once BASE_PATH.'/tests/application/ControllerTestCase.php';
