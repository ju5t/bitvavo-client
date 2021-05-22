<?php

namespace Bitvavo\Models;

use DateTimeZone;
use Carbon\Carbon;
use Bitvavo\Bitvavo;

class WithdrawalHistory extends Model
{
    protected static $endpoint = 'withdrawalHistory';

    protected $guarded = [
        'timestamp',
        'date',
        'symbol',
        'amount',
        'address',
        'paymentId',
        'txId',
        'fee',
    ];

    protected $appends = [
        'date',
    ];

    public function getDateAttribute()
    {
        return Carbon::parse($this->timestamp / 1000, new DateTimeZone('UTC'))
            ->timezone(new DateTimeZone(Bitvavo::getTimezone()));
    }
}
