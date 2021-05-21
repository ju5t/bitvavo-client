<?php

namespace Bitvavo;

use Bitvavo\Exceptions\InvalidClassRequestedException;
use Bitvavo\Exceptions\NotBoundToContainerException;
use Bitvavo\Interfaces\API;
use Illuminate\Container\Container;

/**
 * The App class contains some logic for those who prefer a service
 * container of some sort.
 */
class App
{
    public static ?Container $container = null;

    public static ?API $api = null;

    public static function withContainer() : void
    {
        $container = new Container;
        Container::setInstance($container);
        static::$container = $container;
    }

    /**
     * setInstance creates a static API instance that can
     * be retrieved using resolve().
     *
     * @param API $api
     * @return void
     */
    public static function setInstance(API $api) : void
    {
        static::$api = $api;
    }

    public static function resolve($abstract, $parameters = [])
    {
        if (! empty(static::$container)) {
            $instance = Container::getInstance();
            return $instance->make($abstract, $parameters);
        }

        /** If we're not using a container, we expect resolve to
         * be passed an API::class. If not, raise an exception. */
        if (! is_a($abstract, API::class, true)) {
            throw new InvalidClassRequestedException();
        }

        return static::$api;
    }

    public static function bind($abstract, $callable)
    {
        if (empty(static::$container)) {
            throw new NotBoundToContainerException('Use withContainer() to bind new classes');
        }

        static::$container->bind($abstract, $callable);
    }
}
