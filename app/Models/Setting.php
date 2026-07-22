<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->castValue($setting->value) : $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => is_array($value) ? json_encode($value) : $value,
            ]
        );
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->pluck('value', 'key')
            ->map(fn($value) => json_decode($value, true) ?? $value)
            ->toArray();
    }

    public static function getMultiple(array $keys): array
    {
        $settings = static::whereIn('key', $keys)->get()->pluck('value', 'key')->toArray();

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = isset($settings[$key]) ? (json_decode($settings[$key], true) ?? $settings[$key]) : null;
        }

        return $result;
    }

    public static function forget(string $key): bool
    {
        return (bool) static::where('key', $key)->delete();
    }

    public static function forgetGroup(string $group): int
    {
        return static::where('group', $group)->delete();
    }

    private function castValue(mixed $value): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
