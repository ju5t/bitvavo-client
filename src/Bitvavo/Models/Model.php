<?php

namespace Bitvavo\Models;

use Illuminate\Support\Collection;
use Jenssegers\Model\Model as StaticModel;

abstract class Model extends StaticModel
{
    public static Model $builder;

    public function __construct(...$parameters)
    {
        parent::__construct($parameters);
    }

    public static function make(...$attributes) : Model
    {
        $model = new static(...$attributes);

        return $model;
    }

    public static function query()
    {
        /** We can probably improve this concept */
        if (empty(static::$builder)) {
            static::$builder = new static;
        }

        return static::$builder;
    }

    protected static function asCollection($items) : Collection
    {
        $collection = new Collection();

        foreach ($items as $item) {
            $model = static::unguarded(
                function () use ($item) {
                    return static::make(...$item);
                }
            );

            $collection->add($model);
        }

        return $collection;
    }
}
