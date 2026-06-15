<?php

namespace App\Repositories\Implementations;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): Paginator
    {
        $query = $this->model->select($columns);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->findOrFail($id);
    }

    public function findBy(string $key, string $value, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns)->where($key, $value);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    public function findByUuid(string $uuid, array $columns = ['*'], array $relations = [])
    {
        return $this->findBy('uuid', $uuid, $columns, $relations);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function forceDelete(int $id): bool
    {
        $model = $this->model->withTrashed()->findOrFail($id);
        return $model->forceDelete();
    }

    public function restore(int $id): bool
    {
        $model = $this->model->withTrashed()->findOrFail($id);
        return $model->restore();
    }

    public function count(): int
    {
        return $this->model->count();
    }
}
