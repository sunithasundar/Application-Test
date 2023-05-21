<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function createProduct(string $filename, array $data): array; //allows to create product by passing the array set

    public function readProduct(string $filename): array; //allows to read product by passing the filename

    public function updateProduct(string $filename, array $data, int $rowIndex): void; //allows to update product by passing the array set and the rows id for which update operation is been carried

    public function deleteProduct(string $filename, $rowIndex): void; //allows to delete product by passing row id
}
?>