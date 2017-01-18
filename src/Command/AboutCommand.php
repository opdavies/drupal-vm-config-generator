<?php

namespace DrupalVm\Command;

use DrupalVm\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AboutCommand extends Command
{
    /*
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('about')
             ->setDescription('Display information about the Drupal VM CLI');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->io;

        /** @var Application $application */
        $application = $this->getApplication();

        $io->title(sprintf(
            '%s (%s) | Supports Drupal VM %s',
            $application->getName(),
            $application->getVersion(),
            $application::SUPPORTED_DRUPAL_VM_VERSION
        ));

        $commands = [
            [
                'Initialise Drupal VM CLI.',
                'drupalvm init --overwrite',
            ],
            [
                'Download the latest stable version of Drupal VM.',
                'drupalvm new',
            ],
            [
                'Generate a new configuration file.',
                'drupalvm config:generate',
            ],
            [
                'List all available commands.',
                'drupalvm list',
            ],
        ];

        foreach ($commands as $command) {
            $io->comment($command[0]);
            $io->newLine();
            $io->text($command[1]);
            $io->newLine();
        }
    }
}
