<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []);
    public function find(int $id, array $columns = ['*'], array $relations = []);
    public function findBy(string $key, string $value, array $columns = ['*'], array $relations = []);
    public function findByUuid(string $uuid, array $columns = ['*'], array $relations = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function forceDelete(int $id): bool;
    public function restore(int $id): bool;
    public function count(): int;
}
