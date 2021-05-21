<?php

use Illuminate\Support\Collection;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

function ddd(...$variables)
{
    $cloner = new VarCloner();

    $dumper = new CliDumper();
    $dumper->dump($cloner->cloneVar(
        count($variables) > 1
            ? $variables
            : $variables[0]
        )
    );

    die(1);
}

if (! function_exists('collect')) {
    function collect(array $items = []) : Collection
    {
        return new Collection($items);
    }
}
