<?php

require __DIR__ . '/cli-config.php';

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\QuestionHelper;

/* @var $cli \Symfony\Component\Console\Application */
$cli = $application->getServiceManager()->get('doctrine.cli');
$config = $application->getServiceManager()->get('config');

$arguments = new ArgvInput();
$documentManagerName = $arguments->getParameterOption('--dm');
$documentManagerName = !empty($documentManagerName) ? 'doctrine.documentmanager.'.$documentManagerName : DocumentManager::class ;
$documentManager = $application->getServiceManager()->get($documentManagerName);
$documentHelper  = new \Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper($documentManager);

$helperSet = new HelperSet(array(
    'dm' => $documentHelper,
    'question' => new QuestionHelper()
));

$fixturesConfig = (isset($config['odm-data-fixtures']))? $config['odm-data-fixtures'] : null;

$cli->setHelperSet($helperSet);

$cli->addCommands(array(
    new \DoctrineMongoODMDatafixture\Command\DoctrineMongoODMDatafixtureCommand($fixturesConfig),
    new \DoctrineMongoODMDatafixture\Command\DoctrineMongoODMDatafixtureListCommand($fixturesConfig),
));

$cli->setName('DoctrineMongoODMDatafixture Command Line Interface');
$cli->run();
