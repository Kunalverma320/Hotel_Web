<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DatabaseBackupJob implements ShouldQueue
{
    use Queueable;

    public string $type;
    public ?int $userId;

    public function __construct(string $type = 'manual', ?int $userId = null)
    {
        $this->type = $type;
        $this->userId = $userId;
    }

    public function handle(BackupService $backupService): void
    {
        try {
            $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            if (!is_dir(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            $command = match ($connection) {
                'mysql' => sprintf(
                    'mysqldump -u%s %s %s > %s',
                    $config['username'],
                    $config['password'] ? '-p' . $config['password'] : '',
                    $config['database'],
                    $path
                ),
                'pgsql' => sprintf(
                    'PGPASSWORD="%s" pg_dump -U %s -h %s %s > %s',
                    $config['password'],
                    $config['username'],
                    $config['host'] ?? 'localhost',
                    $config['database'],
                    $path
                ),
                'sqlite' => sprintf(
                    'cp %s %s',
                    $config['database'],
                    $path
                ),
                default => throw new \RuntimeException("Unsupported database connection: {$connection}"),
            };

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            $fileSize = file_exists($path) ? filesize($path) : 0;

            if ($resultCode === 0 && $fileSize > 0) {
                $backup = Backup::create([
                    'filename' => $filename,
                    'file_path' => $path,
                    'file_size' => $fileSize,
                    'type' => $this->type,
                    'status' => 'completed',
                    'created_by' => $this->userId,
                    'notes' => 'Auto-generated ' . $this->type . ' backup',
                ]);

                $this->cleanOldBackups();

                activity()
                    ->performedOn($backup)
                    ->event('backup_created')
                    ->log('Database backup created: ' . $filename);
            } else {
                throw new \RuntimeException('Backup command failed with code: ' . $resultCode);
            }
        } catch (\Exception $e) {
            Log::error('DatabaseBackupJob failed', [
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);

            if (isset($filename)) {
                Backup::create([
                    'filename' => $filename ?? 'unknown',
                    'type' => $this->type,
                    'status' => 'failed',
                    'notes' => 'Error: ' . $e->getMessage(),
                    'created_by' => $this->userId,
                ]);
            }
        }
    }

    protected function cleanOldBackups(): void
    {
        $maxBackups = (int) config('app.max_backups', 30);

        $backups = Backup::where('type', $this->type)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($backups->count() > $maxBackups) {
            $toDelete = $backups->slice($maxBackups);
            foreach ($toDelete as $backup) {
                if ($backup->file_path && file_exists($backup->file_path)) {
                    unlink($backup->file_path);
                }
                $backup->delete();
            }
        }
    }
}
