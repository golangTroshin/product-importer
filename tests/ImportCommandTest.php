<?php

use PHPUnit\Framework\TestCase;
use App\Service\ImportService;
use App\Command\ImportCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends TestCase
{
    public function testCommandFailsWithInvalidPath(): void
    {
        $mockService = $this->createMock(ImportService::class);
        $command = new ImportCommand($mockService);

        $tester = new CommandTester($command);
        $tester->execute(['--path' => 'invalid.csv']);

        $this->assertStringContainsString('Invalid or missing file path', $tester->getDisplay());
    }

    public function testCommandDetectsCsvAndRuns(): void
    {
        $fixturePath = __DIR__ . '/fixtures/feed.csv';
        $this->assertFileExists($fixturePath);

        $mockService = $this->createMock(ImportService::class);
        $mockService->expects($this->once())
            ->method('import')
            ->with($fixturePath, 1, 3)
            ->willReturn([]); // Simulate successful import (no errors)

        $command = new ImportCommand($mockService);
        $tester = new CommandTester($command);

        $tester->execute([
            '--path' => $fixturePath,
            '--start' => 1,
            '--end' => 3
        ]);

        $this->assertStringContainsString('Import completed successfully', $tester->getDisplay());
    }

    public function testCommandHandlesImportErrors(): void
    {
        $fixturePath = __DIR__ . '/fixtures/feed.csv';

        $mockService = $this->createMock(ImportService::class);
        $mockService->expects($this->once())
            ->method('import')
            ->willReturn([
                'GTIN 1234567890123: Duplicate entry',
                'GTIN 2345678901234: Invalid price format',
            ]);

        $command = new ImportCommand($mockService);
        $tester = new CommandTester($command);

        $tester->execute([
            '--path' => $fixturePath,
            '--start' => 0,
            '--end' => 2
        ]);

        $display = $tester->getDisplay();
        $this->assertStringContainsString('Import completed with 2 error(s):', $display);
        $this->assertStringContainsString('GTIN 1234567890123', $display);
        $this->assertStringContainsString('Duplicate entry', $display);
    }

}
