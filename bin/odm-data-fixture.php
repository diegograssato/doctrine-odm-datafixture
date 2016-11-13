<?php

require __DIR__ . '/cli-config.php';

$app = new \Symfony\Component\Console\Application('Doctrine Mongo ODM Datafixture - ' . \DoctrineMongoODMDatafixture\Module::VERSION);

if (isset($helperSet)) {
    $app->setHelperSet($helperSet);
}

$app->addCommands(array(
    new \DoctrineMongoODMDatafixture\Command\DoctrineMongoODMDatafixtureCommand(),
));

$app->run();
