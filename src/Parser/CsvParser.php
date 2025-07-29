<?php

namespace App\Parser;

use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;

class CsvParser implements ParserInterface
{
    /**
     * @throws InvalidArgument
     * @throws UnavailableStream
     * @throws SyntaxError
     * @throws Exception
     */
    public function getData(string $filePath, int $startRow = 0, ?int $endRow = null): array
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0);

        $stmt = (new Statement())->offset($startRow);

        if ($endRow !== null) {
            $stmt = $stmt->limit($endRow - $startRow);
        }

        return array_values(iterator_to_array($stmt->process($csv)));
    }
}
