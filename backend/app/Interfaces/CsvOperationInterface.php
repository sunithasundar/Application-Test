<?php

namespace App\Interfaces;

interface CsvOperationInterface
{

    public function create(string $filename, array $data): array;

    public function read(string $filename): array;

    public function update(string $filename, array $data, int $rowIndex): void;

    public function deleteById(string $filename, $rowIndex): void;
}
?>