<?php
use Zend\Mvc\Application;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

$env = (isset($_SERVER['APPLICATION_ENV'])) ? $_SERVER['APPLICATION_ENV'] : 'development';

define('ENV', $env);

/**
 * ZF2 command line tool
 *
 * @link      http://github.com/zendframework/ZFLabsODMFixture for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
$basePath = getcwd();
ini_set('user_agent', 'ZFLabsODMFixture - Zend Framework 2 command line tool');
// load autoloader
if (file_exists("$basePath/vendor/autoload.php")) {
    require_once "$basePath/vendor/autoload.php";
} elseif (file_exists("$basePath/init_autoload.php")) {
    require_once "$basePath/init_autoload.php";
} elseif (\Phar::running()) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    echo 'Error: I cannot find the autoloader of the application.' . PHP_EOL;
    echo "Check if $basePath contains a valid ZF2 application." . PHP_EOL;
    exit(2);
}

// Retrieve configuration
$appConfig = require $basePath . '/config/application.config.php';
if (file_exists($basePath . '/config/development.config.php')) {
    $appConfig = \Zend\Stdlib\ArrayUtils::merge($appConfig, require  $basePath . '/config/development.config.php');
}

$app = Application::init($appConfig);
$dm = $app->getServiceManager()->get(DocumentManager::class);

