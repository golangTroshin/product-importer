<?php

namespace App\Parser;

use InvalidArgumentException;

class ParserFactory
{
    /**
     * @param string $extension
     * @return ParserInterface
     */
    public function createFromExtension(string $extension): ParserInterface
    {
        return match (strtolower($extension)) {
            'csv' => new CsvParser(),
            // 'xml' => new XmlParser(), // future formats
            default => throw new InvalidArgumentException("Unsupported file type: .$extension"),
        };
    }
}
