<?php

namespace Bitvavo\Models;

class Asset extends Model
{
    protected static $endpoint = 'assets';

    protected $guarded = [
        'symbol',
        'name',
        'decimals',
        'depositFee',
        'depositConfirmation',
        'depositStatus',
        'withDrawalFee',
        'withDrawalMinAmount',
        'WithDrawalStatus',
        'networks',
        'message',
    ];
}
