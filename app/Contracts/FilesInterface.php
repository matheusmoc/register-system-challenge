<?php
namespace App\Contracts;

interface FilesInterface
{
    public function upload($file): string;
    public function delete(string $path): bool;
}