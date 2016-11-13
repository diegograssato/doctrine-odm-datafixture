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
 * @since   1.0
 * @author  Diego Pereira Grassoto <diego.grassato@gmail.com>
 */
class DoctrineMongoODMDatafixtureCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('odm:fixture:load')
            ->setDescription('Load data fixtures to your database.')
            ->addOption('fixtures', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory to load data fixtures from.')
            ->addOption('dm', null, InputOption::VALUE_OPTIONAL, 'Set document manager.')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ->setHelp(<<<EOT
The <info>odm:fixture:load</info> command loads data fixtures from your bundles:
  <info>php public/index.php odm:fixture:load</info>
You can also optionally specify the path to fixtures with the <info>--fixtures</info> option:
  <info>php public/index.php odm:fixture:load --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2</info>
If you want to append the fixtures instead of flushing the database first you can use the <info>--append</info> option:
  <info>php public/index.php odm:fixture:load --append</info>

EOT
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', "Loading fixtures"));
        $output->writeln(sprintf('<comment>%s</comment>', "------------------\n"));

        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($this->getDocumentManager(), $purger);
        $executor->setLogger(function ($message) use ($output) {
            $output->writeln(sprintf('  <comment>✔</comment> <info>%s</info>', $message));
        });
        $loader = new Loader();

        $dirOrFile = $input->getOption('fixtures');
        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);
            $paths = array_unique($paths);
        } else {
            $paths = $this->findFixtureInApplication();
        }

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            } elseif (is_file($path)) {
                $loader->loadFromFile($path);
            }
        }

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new \RuntimeException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
            );
        }

        $executor->execute($fixtures, $input->getOption('append'));
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

    /**
     * @param SchemaManager $sm
     * @param object $document
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
     * @param object $document
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
     * @param object $document
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
