<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Guest;
use App\Models\InventoryItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProcessImportJob implements ShouldQueue
{
    use Queueable;

    public string $filePath;
    public string $importType;
    public array $mappings;
    public ?int $userId;
    public int $hotelId;

    public function __construct(string $filePath, string $importType, array $mappings = [], ?int $userId = null, int $hotelId = 0)
    {
        $this->filePath = $filePath;
        $this->importType = $importType;
        $this->mappings = $mappings;
        $this->userId = $userId;
        $this->hotelId = $hotelId;
    }

    public function handle(): void
    {
        try {
            if (!file_exists($this->filePath)) {
                Log::error('Import file not found', ['path' => $this->filePath]);
                return;
            }

            $rows = Excel::toArray([], $this->filePath)[0] ?? [];

            if (empty($rows)) {
                Log::warning('Import file is empty', ['path' => $this->filePath]);
                return;
            }

            $headers = array_shift($rows);
            $successCount = 0;
            $failCount = 0;
            $errors = [];

            DB::transaction(function () use ($rows, $headers, &$successCount, &$failCount, &$errors) {
                foreach ($rows as $index => $row) {
                    $rowNumber = $index + 2;
                    $data = [];

                    foreach ($headers as $i => $header) {
                        $field = $this->mappings[$header] ?? $header;
                        $data[$field] = $row[$i] ?? null;
                    }

                    $data['hotel_id'] = $this->hotelId;

                    try {
                        $model = match ($this->importType) {
                            'guests' => $this->importGuest($data),
                            'inventory' => $this->importInventoryItem($data),
                            'employees' => $this->importEmployee($data),
                            default => throw new \InvalidArgumentException("Unknown import type: {$this->importType}"),
                        };

                        if ($model) {
                            $successCount++;
                        } else {
                            $failCount++;
                            $errors[] = "Row {$rowNumber}: Validation failed";
                        }
                    } catch (\Exception $e) {
                        $failCount++;
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    }
                }
            });

            activity()
                ->withProperties([
                    'import_type' => $this->importType,
                    'file' => $this->filePath,
                    'success_count' => $successCount,
                    'fail_count' => $failCount,
                    'errors' => $errors,
                ])
                ->event('import_completed')
                ->log("Import completed: {$successCount} succeeded, {$failCount} failed");
        } catch (\Exception $e) {
            Log::error('ProcessImportJob failed', [
                'import_type' => $this->importType,
                'file' => $this->filePath,
                'error' => $e->getMessage(),
            ]);
        } finally {
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
        }
    }

    protected function importGuest(array $data): ?Guest
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return null;
        }

        return Guest::create($validator->validated());
    }

    protected function importInventoryItem(array $data): ?InventoryItem
    {
        $validator = Validator::make($data, [
            'hotel_id' => 'required|integer|exists:hotels,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:inventory_items,sku',
            'unit' => 'nullable|string|max:50',
            'current_stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return null;
        }

        $validated = $validator->validated();
        $validated['is_active'] = true;

        return InventoryItem::create($validated);
    }

    protected function importEmployee(array $data): ?Employee
    {
        $validator = Validator::make($data, [
            'hotel_id' => 'required|integer|exists:hotels,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'employee_id' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return null;
        }

        $validated = $validator->validated();
        $validated['status'] = 'active';

        return Employee::create($validated);
    }
}
