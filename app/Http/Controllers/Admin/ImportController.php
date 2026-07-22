<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'module' => 'required|string|in:bookings,guests,rooms,employees,inventory',
        ]);

        $file = $request->file('file');
        $module = $request->input('module');

        // TODO: Parse Excel file and store in session for preview

        $parsedData = $this->parseFile($file);

        session(['import_data' => $parsedData, 'import_module' => $module]);

        return redirect()->route('admin.import.preview');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|mimes:csv|max:10240',
            'module' => 'required|string|in:bookings,guests,rooms,employees,inventory',
        ]);

        $file = $request->file('file');
        $module = $request->input('module');

        $parsedData = $this->parseFile($file);

        session(['import_data' => $parsedData, 'import_module' => $module]);

        return redirect()->route('admin.import.preview');
    }

    public function preview(Request $request)
    {
        $data    = session('import_data', []);
        $module  = session('import_module', '');
        $headers = !empty($data) ? array_keys($data[0]) : [];
        $total   = count($data);

        return view('admin.import.index', compact('data', 'module', 'headers', 'total'));
    }

    public function processImport(Request $request)
    {
        $data   = session('import_data', []);
        $module = session('import_module', '');

        if (empty($data)) {
            return redirect()->route('admin.import.index')->with('error', 'No data to import.');
        }

        // TODO: Import data into the database based on $module

        session()->forget(['import_data', 'import_module']);

        return redirect()->route('admin.import.index')->with('success', count($data) . " records imported successfully into {$module}.");
    }

    private function parseFile($file)
    {
        $rows = [];
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $rows[] = array_combine($headers, $row);
            }
        }

        fclose($handle);

        return $rows;
    }
}
