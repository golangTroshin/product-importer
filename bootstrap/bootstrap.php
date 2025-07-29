<?php

use App\Command\ImportCommand;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $builder = new ContainerBuilder();

    $container = $builder->build();

    $application = new Application();

    $application->add($container->get(ImportCommand::class));

    return $application;
} catch (Throwable $e) {
    fwrite(STDERR, "Bootstrap error: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
