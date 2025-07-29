<?php

use App\Database\ConnectionProvider;
use App\Parser\ParserFactory;
use App\Parser\ParserInterface;
use App\Service\ImportService;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class ImportServiceTest extends TestCase
{
    public function testImportReturnsErrors(): void
    {
        $fakeData = [
            ['gtin' => '111', 'title' => 'Bad Product'],
            ['gtin' => '222', 'title' => 'Another Bad Product']
        ];

        $mockParser = $this->createMock(ParserInterface::class);
        $mockParser->method('getData')->willReturn($fakeData);

        $mockFactory = $this->createMock(ParserFactory::class);
        $mockFactory->method('createFromExtension')->willReturn($mockParser);

        $mockConnection = $this->createMock(Connection::class);
        $mockConnection->method('insert')
            ->willThrowException(new \Exception('Simulated insert failure'));

        $mockDbProvider = $this->createMock(ConnectionProvider::class);
        $mockDbProvider->method('getConnection')->willReturn($mockConnection);

        $service = new ImportService($mockFactory, $mockDbProvider);
        $errors = $service->import('fake.csv', 0, 2);

        $this->assertCount(2, $errors);
        $this->assertStringContainsString('GTIN', $errors[0]);
        $this->assertStringContainsString('Simulated insert failure', $errors[0]);
    }
}
