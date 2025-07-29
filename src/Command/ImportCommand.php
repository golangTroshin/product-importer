<?php

namespace App\Command;

use App\Service\ImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    private ImportService $importService;

    public function __construct(ImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    protected function configure(): void
    {
        $this
            ->setName('import:file')
            ->setDescription('Imports data from supported file types into DB.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path to the file')
            ->addOption('start', 's', InputOption::VALUE_OPTIONAL, 'Start row (for formats that support it)')
            ->addOption('end', 'e', InputOption::VALUE_OPTIONAL, 'End row number (exclusive, for formats that support it)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getOption('path');
        $startOption = $input->getOption('start');
        $endOption = $input->getOption('end');

        $start = is_numeric($startOption) ? (int)$startOption : 0;
        $end = is_numeric($endOption) ? (int)$endOption : null;

        if (!$path || !file_exists($path)) {
            $output->writeln('<error>Invalid or missing file path.</error>');
            return Command::FAILURE;
        }

        $output->writeln("Importing from <info>$path</info> from row <info>$start</info>" .
            ($end !== null ? " to <info>$end</info>" : " to end"));

        $errors = $this->importService->import($path, $start, $end);

        if (empty($errors)) {
            $output->writeln('<info>Import completed successfully.</info>');
            return Command::SUCCESS;
        } else {
            $output->writeln("<comment>Import completed with " . count($errors) . " error(s):</comment>");
            foreach ($errors as $error) {
                $output->writeln(" - $error");
            }
            return Command::FAILURE;
        }
    }
}
