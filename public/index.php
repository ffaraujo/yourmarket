<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('PATH_UPLOAD') || define('PATH_UPLOAD', dirname(__FILE__) . '/upload/');

//define flashMessage cons
define('ERROR', 0);
define('SUCCESS', 1);
define('WARNING', 2);

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('America/Recife');

require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$lang = new MsgsTranslate();
$translate = new Zend_Translate('array', $lang->getTranslation(), 'pt_BR'); 
Zend_Registry::set('translate', $translate); 

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
        ->run();
