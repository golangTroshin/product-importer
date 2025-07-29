<?php

namespace App\Service;

use App\Database\ConnectionProvider;
use App\Parser\ParserFactory;

class ImportService
{
    private ParserFactory $parserFactory;
    private ConnectionProvider $connectionProvider;

    public function __construct(ParserFactory $parserFactory, ConnectionProvider $connectionProvider)
    {
        $this->parserFactory = $parserFactory;
        $this->connectionProvider = $connectionProvider;
    }

    /**
     * @return array<string> List of error messages keyed by GTIN or index
     */
    public function import(string $filePath, int $startRow = 0, ?int $endRow = null): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $parser = $this->parserFactory->createFromExtension($extension);

        $connection = $this->connectionProvider->getConnection();

        try {
            $rows = $parser->getData($filePath, $startRow, $endRow);
        } catch (\Throwable $e) {
            throw new \RuntimeException("Failed to parse CSV: " . $e->getMessage(), 0, $e);
        }

        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                $connection->insert('products', $row);
            } catch (\Throwable $e) {
                $gtin = 'GTIN '. $row['gtin'] ?? "ROW #$index";
                $errors[] = "$gtin: " . $e->getMessage();
            }
        }

        return $errors;
    }
}
