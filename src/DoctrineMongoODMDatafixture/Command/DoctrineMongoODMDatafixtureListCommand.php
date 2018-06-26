<?php

namespace DoctrineMongoODMDatafixture\Command;

use Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\AbstractCommand;
use Doctrine\ODM\MongoDB\SchemaManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;

/**
 * Command to create the database schema for a set of classes based on their mappings.
 *
 * @since  1.0
 * @author Diego Pereira Grassoto <diego.grassato@gmail.com>
 */
class DoctrineMongoODMDatafixtureListCommand extends AbstractCommand
{
    protected $paths = [];
    protected $fixturesConfig = [];

    public function __construct($fixturesConfig = null)
    {
        $this->fixturesConfig = $fixturesConfig;

        parent::__construct("DoctrineMongoODMDatafixtureListCommand");
    }

    protected function configure()
    {
        $this
            ->setName('odm:fixtures:list')
            ->setDescription('Lists data fixtures to your database.')
            ->addOption('fixture', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory to load data fixtures from.')
            ->addOption('dm', null, InputOption::VALUE_OPTIONAL, 'Set document manager.')
            ->addOption('group', null, InputOption::VALUE_OPTIONAL, 'Set group.')
            ->setHelp(
                <<<EOT
The <info>odm:fixtures:list</info> command loads data fixtures from your bundles:
  <info>vendor/bin/doctrine-module odm:fixtures:list</info>
You can also optionally specify the path to fixtures with the <info>--fixture</info> option:
  <info>vendor/bin/doctrine-module odm:fixtures:list --fixture=/path/to/fixtures1 --fixture=/path/to/fixtures2</info>
  or
  <info>vendor/bin/doctrine-module odm:fixtures:list --fixture /path/to/fixtures1 --fixture /path/to/fixtures2</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', "Listing ODM fixtures."));
        $output->writeln(sprintf('<comment>%s</comment>', "----------------------\n"));

        $loader = new Loader();
        $dirOrFile = $input->getOption('fixture');
        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);
            $this->paths = array_unique($paths);
        } else {

            $this->getPathFromConig($input, $output);
        }

        foreach ($this->paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            } elseif (is_file($path)) {
                $loader->loadFromFile($path);
            }
        }

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new \RuntimeException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $this->paths))
            );
        }
        foreach ($fixtures as $fixture) {
            $output->writeln(sprintf('  <comment>✔</comment> <info>%s</info>', get_class($fixture)));
        }
        $output->writeln("");
    }


    protected function findFixtureInApplication()
    {
        $basePath = getcwd();
        $paths = array();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basePath. '/module'), \RecursiveIteratorIterator::CATCH_GET_CHILD);

        foreach ($iterator as $item) {
            $folders = [
                $item."/src/Fixtures", $item."/src/Document/Fixtures",
                $item."/src/Fixtures/MongoDB", $item."/src/Document/Fixtures/MongoDB",
                $item."/src/Fixtures/ODM", $item."/src/Document/Fixtures/ODM",

                $item."/src/Fixture", $item."/src/Document/Fixture",
                $item."/src/Fixture/MongoDB", $item."/src/Document/Fixture/MongoDB",
                $item."/src/Fixture/ODM", $item."/src/Document/Fixture/ODM",

                $item."/src/DataFixtures",  $item."/src/Document/DataFixtures",
                $item."/src/DataFixtures/MongoDB",  $item."/src/Document/DataFixtures/MongoDB",
                $item."/src/DataFixtures/ODM",  $item."/src/Document/DataFixtures/ODM",

                $item."/src/DataFixture",  $item."/src/Document/DataFixture",
                $item."/src/DataFixture/MongoDB",  $item."/src/Document/DataFixture/MongoDB",
                $item."/src/DataFixture/ODM",  $item."/src/Document/DataFixture/ODM"
            ];
            foreach ($folders as $folder) {
                if (is_dir($folder)) {
                    array_push($paths, $folder);
                }
            }
        }

        return $paths;
    }
    protected function isGroupSupport()
    {
        if (count($this->fixturesConfig) === 0) {
            return false;
        }

        return array_key_exists('groups', $this->fixturesConfig);
    }

    protected function getPathFromConig(InputInterface $input, OutputInterface $output)
    {
        if ($this->isGroupSupport()) {

            $group = $input->getOption('group');
            if (isset($this->fixturesConfig['groups']['default']) && empty($group)) {

                $this->paths = $this->fixturesConfig['groups']['default'];
                $output->writeln(sprintf('<comment>%s</comment>', "Loading [ default ] group."));

            } elseif (isset($this->fixturesConfig['groups'][$group])) {

                $this->paths = $this->fixturesConfig['groups'][$group];
                $output->writeln(sprintf('<comment>%s</comment>', "Loading [ $group ] group."));

            }

        } elseif (count($this->fixturesConfig) > 0) {

            $this->paths = $this->fixturesConfig;
            $output->writeln(sprintf('<comment>%s</comment>', "Loading path from configuration file."));

        } elseif (empty($this->paths)) {

            $output->writeln(sprintf('<comment>%s</comment>', "Detecting fixture in application."));
            $this->paths = $this->findFixtureInApplication();

        }
    }
    /**
     * @param SchemaManager $sm
     * @param object        $document
     */
    protected function processDocumentIndex(SchemaManager $sm, $document)
    {
        throw new \BadMethodCallException('Cannot update a document collection');
    }

    /**
     * @param SchemaManager $sm
     */
    protected function processIndex(SchemaManager $sm)
    {
        throw new \BadMethodCallException('Cannot update a document collection');
    }

    /**
     * @param SchemaManager $sm
     * @param object        $document
     * @throws \BadMethodCallException
     */
    protected function processDocumentCollection(SchemaManager $sm, $document)
    {
        throw new \BadMethodCallException('Cannot update a document collection');
    }

    /**
     * @param SchemaManager $sm
     * @throws \BadMethodCallException
     */
    protected function processCollection(SchemaManager $sm)
    {
        throw new \BadMethodCallException('Cannot update a collection');
    }

    /**
     * @param SchemaManager $sm
     * @param object        $document
     * @throws \BadMethodCallException
     */
    protected function processDocumentDb(SchemaManager $sm, $document)
    {
        throw new \BadMethodCallException('Cannot update a document database');
    }

    /**
     * @param SchemaManager $sm
     * @throws \BadMethodCallException
     */
    protected function processDb(SchemaManager $sm)
    {
        throw new \BadMethodCallException('Cannot update a database');
    }
}
