<?php

namespace Bitvavo;

use Bitvavo\Models\Model;
use Illuminate\Support\Collection;

class Builder
{
    public array $bindings = [
        'where' => [],
    ];

    public function __construct(private Model $model)
    {}

    public function where($column, $value = null)
    {
        $this->bindings['where'][$column] = $value;
        return $this;
    }

    public function getWhereBinding()
    {
        return $this->bindings['where'];
    }

    public function first() : Model
    {
        return $this->model->first();
    }

    public function all() : Collection
    {
        return $this->model->all();
    }

    public function get() : Collection
    {
        return $this->model->get();
    }
}
