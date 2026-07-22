<?php

namespace App\Services;

use App\Models\Backup;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    protected string $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
    }

    public function databaseBackup(): array
    {
        $filename = 'db_backup_' . now()->format('Y-m-d_His') . '.sql';
        $path = "{$this->backupPath}/database/{$filename}";

        File::makeDirectory(dirname($path), 0755, true, true);

        $exitCode = Artisan::call('backup:run', [
            '--only-db' => true,
        ]);

        if ($exitCode !== 0) {
            throw new \RuntimeException('Database backup failed: ' . Artisan::output());
        }

        $backup = Backup::create([
            'type' => 'database',
            'filename' => $filename,
            'path' => $path,
            'size' => file_exists($path) ? filesize($path) : 0,
            'status' => 'completed',
            'created_at' => now(),
        ]);

        return $backup->toArray();
    }

    public function fileBackup(): array
    {
        $filename = 'files_backup_' . now()->format('Y-m-d_His') . '.zip';
        $path = "{$this->backupPath}/files/{$filename}";

        File::makeDirectory(dirname($path), 0755, true, true);

        $filesToBackup = [
            storage_path('app/public'),
            public_path('uploads'),
        ];

        $zip = new \ZipArchive();
        if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($filesToBackup as $dir) {
                if (File::isDirectory($dir)) {
                    $files = File::allFiles($dir);
                    foreach ($files as $file) {
                        $relativePath = ltrim(str_replace(base_path(), '', $file->getPathname()), DIRECTORY_SEPARATOR);
                        $zip->addFile($file->getPathname(), $relativePath);
                    }
                }
            }
            $zip->close();
        }

        $backup = Backup::create([
            'type' => 'files',
            'filename' => $filename,
            'path' => $path,
            'size' => file_exists($path) ? filesize($path) : 0,
            'status' => 'completed',
            'created_at' => now(),
        ]);

        return $backup->toArray();
    }

    public function fullBackup(): array
    {
        $dbBackup = $this->databaseBackup();
        $fileBackup = $this->fileBackup();

        return [
            'database' => $dbBackup,
            'files' => $fileBackup,
        ];
    }

    public function restore(int|string $backupId): bool
    {
        $backup = Backup::findOrFail($backupId);

        if (!file_exists($backup->path)) {
            throw new \RuntimeException("Backup file not found: {$backup->path}");
        }

        if ($backup->type === 'database') {
            $exitCode = Artisan::call('backup:restore', [
                '--backup-id' => $backupId,
            ]);

            return $exitCode === 0;
        }

        $zip = new \ZipArchive();
        if ($zip->open($backup->path) === true) {
            $zip->extractTo(base_path());
            $zip->close();
            return true;
        }

        return false;
    }

    public function getScheduledBackups(): \Illuminate\Database\Eloquent\Collection
    {
        return Backup::where('is_scheduled', true)
            ->orderByDesc('next_run_at')
            ->get();
    }

    public function deleteBackup(int|string $id): bool
    {
        $backup = Backup::findOrFail($id);

        if (file_exists($backup->path)) {
            File::delete($backup->path);
        }

        return $backup->delete();
    }
}
