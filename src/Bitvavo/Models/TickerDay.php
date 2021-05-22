<?php

namespace Bitvavo\Models;

use DateTimeZone;
use Carbon\Carbon;
use Bitvavo\Bitvavo;

class TickerDay extends Model
{
    protected static $endpoint = 'ticker/24h';

    protected $guarded = [
        'market',
        'open',
        'high',
        'low',
        'volume',
        'volumeQuote',
        'bid',
        'bidSize',
        'ask',
        'askSize',
        'timestamp',
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
