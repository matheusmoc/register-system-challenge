<?php
namespace App\Services;

use App\Contracts\FilesInterface;
use Illuminate\Support\Facades\Storage;

class FileService implements FilesInterface
{
    public function upload($file): string
    {
        return $file->store('anexos', 'public');
    }

    public function delete(string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}