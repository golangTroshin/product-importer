<?php

namespace App\Parser;

interface ParserInterface
{
    /**
     * @return iterable Each row as associative array
     */
    public function getData(string $filePath, int $startRow = 0, ?int $endRow = null): iterable;
}
