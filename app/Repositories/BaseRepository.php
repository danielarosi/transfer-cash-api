<?php

namespace App\Repositories;

class BaseRepository
{
    protected $obj;

    protected function __construct(object $obj)
    {
        $this->obj = $obj;
    }

    public function all(): object
    {
        return $this->obj->all();
    }

    public function find(int $id): object
    {
        return $this->obj->find($id);
    }

    public function findFirstByColumn(string $column, $value): ?object
    {
        return $this->obj->where($column, $value)->first();
    }
    
    public function findAllByColumn(string $column, $value): ?object
    {
        return $this->obj->where($column, $value)->get();

    }
    public function save(array $attributes): object
    {
        return $this->obj->create($attributes);
    }
    
}
