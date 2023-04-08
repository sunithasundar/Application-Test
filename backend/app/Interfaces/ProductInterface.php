<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function createProduct(string $filename, array $data): array;

    public function readProduct(string $filename): array;

    public function updateProduct(string $filename, array $data, int $rowIndex): void;

    public function deleteProduct(string $filename, $rowIndex): void;
}
?>