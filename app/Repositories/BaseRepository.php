<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }

    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): Model
    {
        $model = $this->find($id);
        $model->update($data);
        return $model->fresh();
    }

    public function delete(int|string $id): bool
    {
        return $this->find($id)->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function search(string $field, string $value): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where($field, 'like', "%{$value}%")->get();
    }

    public function scopeByHotel(Builder $query, int|string $hotelId): Builder
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function bulkCreate(array $data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->insert($data);
    }

    public function withTrashed(): Builder
    {
        return $this->model->withTrashed();
    }

    public function restore(int|string $id): bool
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function forceDelete(int|string $id): bool
    {
        return $this->model->withTrashed()->find($id)->forceDelete();
    }
}
