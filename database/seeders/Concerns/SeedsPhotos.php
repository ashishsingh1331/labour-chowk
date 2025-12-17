<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait SeedsPhotos
{
    protected function seedPhotoPoolToPublic(string $sourceDir, string $targetDir = 'labourers'): ?string
    {
        if (!File::isDirectory($sourceDir)) {
            return null;
        }

        $files = collect(File::files($sourceDir))
            ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png'], true))
            ->values();

        if ($files->isEmpty()) {
            return null;
        }

        $file = $files->random();

        $ext = strtolower($file->getExtension());
        $name = Str::random(20) . '.' . $ext;
        $relative = $targetDir . '/' . $name;

        $dest = storage_path('app/public/' . $relative);
        File::ensureDirectoryExists(dirname($dest));
        File::copy($file->getRealPath(), $dest);

        return $relative;
    }
}


