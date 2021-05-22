<?php

namespace Bitvavo\Models;

class Balance extends Model
{
    protected static $endpoint = 'balance';

    protected $guarded = [
        'symbol',
        'available',
        'inOrder',
    ];
}
