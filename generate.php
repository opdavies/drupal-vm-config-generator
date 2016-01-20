<?php

use Command\Generate\GenerateCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->addCommands([
    'GenerateCommand' => new GenerateCommand(),
]);

$app->run();
