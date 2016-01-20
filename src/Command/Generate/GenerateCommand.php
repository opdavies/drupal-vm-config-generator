<?php

namespace Command\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateCommand extends Command
{
    private $content = '---';

    protected function configure() {
        $this
            ->setName('generate:config');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $fs = new Filesystem();
        $fs->dumpFile('config.yml', $this->content);
    }
}
