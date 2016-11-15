<?php

namespace DoctrineMongoODMDatafixture;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Symfony\Component\Console\Helper\QuestionHelper;

class Module
{
    const VERSION = '1.0';

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    /**
     * {@inheritDoc}
     */
    public function init(ModuleManagerInterface $manager)
    {
        $events = $manager->getEventManager();
        // Initialize logger collector once the profiler is initialized itself
        $events->attach('profiler_init', function (EventInterface $e) use ($manager) {
            $manager->getEvent()->getParam('ServiceManager')->get('doctrine.mongo_logger_collector.odm_default');
        });
        $events->getSharedManager()->  attach('doctrine', 'loadCli.post', array($this, 'loadCli'));
    }

    /**
     * @param Event $event
     */
    public function loadCli(EventInterface $event)
    {
        $commands = array(
            new \DoctrineMongoODMDatafixture\Command\DoctrineMongoODMDatafixtureCommand(),
            new \DoctrineMongoODMDatafixture\Command\DoctrineMongoODMDatafixtureListCommand()
        );

        foreach ($commands as $command) {
            $command->getDefinition()->addOption(
                new InputOption(
                    'dm',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'The name of the documentmanager to use. If none is provided, it will use odm_default.'
                )
            );
        }

        $cli = $event->getTarget();
        $cli->addCommands($commands);

        $arguments = new ArgvInput();
        $documentManagerName = $arguments->getParameterOption('--dm');
        $documentManagerName = !empty($documentManagerName) ? $documentManagerName : 'odm_default';

        $documentManager = $event->getParam('ServiceManager')->get('doctrine.documentmanager.' . $documentManagerName);
        $documentHelper  = new \Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper($documentManager);
        $cli->getHelperSet()->set($documentHelper, 'dm');
        $cli->getHelperSet()->set(new QuestionHelper(), 'question');
    }
}
