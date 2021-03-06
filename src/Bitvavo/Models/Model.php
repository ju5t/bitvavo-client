<?php

namespace Bitvavo\Models;

use Bitvavo\Bitvavo;
use Bitvavo\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jenssegers\Model\Model as StaticModel;

abstract class Model extends StaticModel
{
    use Macroable;

    protected static $endpoint = '';

    public static Builder $builder;

    public function __construct(...$parameters)
    {
        parent::__construct($parameters);
    }

    public static function make(...$attributes) : Model
    {
        return new static(...$attributes);
    }

    public static function first() : Model
    {
        /** @var \Bitvavo\Builder $builder */
        $builder = static::query();
        return $builder->get()->first();
    }

    public static function all() : Collection
    {
        /** @var \Bitvavo\Builder $builder */
        $builder = static::query();
        return $builder->get();
    }

    public function get() : Collection
    {
        /** @var \Bitvavo\Bitvavo $api */
        $api = Bitvavo::resolve(Bitvavo::class);

        /** @var \Bitvavo\Builder $builder */
        $builder = static::$builder;

        $response = $api->get(endpoint: static::$endpoint, params: $builder->getWhereBinding());
        return static::collection($response);
    }

    public static function query()
    {
        /** We can probably improve this concept */
        if (empty(static::$builder)) {
            static::$builder = new Builder(new static);
        }

        return static::$builder;
    }

    public static function where($column, $value = null) : Builder
    {
        $query = static::query();
        return $query->where($column, $value);
    }

    protected static function collection(array $items) : Collection
    {
        $collection = new Collection();

        if (isset($items[0]) && is_array($items[0])) {
            foreach ($items as $item) {
                $collection->add(static::createUnguardedModel($item));
            }

            return $collection;
        }

        $collection->add(static::createUnguardedModel($items));
        return $collection;
    }

    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            case 'collection':
                return new Collection($this->fromJson($value));
            default:
                return $value;
        }
    }

    private static function createUnguardedModel(array $item) : Model
    {
        return static::unguarded(
            function () use ($item) {
                return static::make(...$item);
            }
        );
    }
}
