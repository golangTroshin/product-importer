<?php

use PHPUnit\Framework\TestCase;
use App\Parser\CsvParser;

class CsvParserTest extends TestCase
{
    private string $fixturePath;

    protected function setUp(): void
    {
        $this->fixturePath = __DIR__ . '/fixtures/feed.csv';
    }

    public function testCsvParserOffsetWorks(): void
    {
        $parser = new CsvParser();
        $data = $parser->getData($this->fixturePath, 1);

        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('title', $data[0]);
        $this->assertEquals('Product 2', $data[0]['title']);
    }

    public function testCsvParserOffsetAndEndLimitsRange(): void
    {
        $parser = new CsvParser();
        $data = $parser->getData($this->fixturePath, 1, 3);

        $this->assertCount(2, $data);
        $this->assertEquals('Product 2', $data[0]['title']);
        $this->assertEquals('Product 3', $data[1]['title']);
    }
}
