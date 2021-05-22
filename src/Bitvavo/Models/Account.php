<?php

namespace Bitvavo\Models;

class Account extends Model
{
    protected static $endpoint = 'account';

    protected $guarded = [
        'fees',
    ];
}
