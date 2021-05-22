<?php

namespace Bitvavo\Models;

class TickerPrice extends Model
{
    protected static $endpoint = 'ticker/price';

    protected $guarded = [
        'market',
        'price',
    ];
}
