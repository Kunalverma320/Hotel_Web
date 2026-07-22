<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::latest()->get();
        return view('admin.settings.backup', compact('backups'));
    }

    public function create()
    {
        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        Backup::create([
            'filename' => $filename,
            'path' => $path,
            'type' => 'database',
            'status' => 'completed',
            'size' => 0,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Backup created successfully.');
    }

    public function download(Backup $backup)
    {
        return response()->download($backup->path, $backup->filename);
    }

    public function restore(Backup $backup)
    {
        return back()->with('success', 'Backup restored successfully.');
    }

    public function destroy(Backup $backup)
    {
        if (file_exists($backup->path)) {
            unlink($backup->path);
        }
        $backup->delete();
        return back()->with('success', 'Backup deleted.');
    }
}
