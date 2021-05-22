<?php

namespace Bitvavo\Models;

use DateTimeZone;
use Carbon\Carbon;
use Bitvavo\Bitvavo;
use Bitvavo\Builder;

class Trade extends Model
{
    protected static $endpoint = 'trades';

    protected $guarded = [
        'id',
        'orderId',
        'timestamp',
        'date',
        'market',
        'amount',
        'price',
        'taker',
        'fee',
        'feeCurrency',
        'settled',
    ];

    protected $casts = [
        'taker' => 'boolean',
        'settled' => 'boolean',
    ];

    protected $appends = [
        'date',
    ];

    public function getDateAttribute()
    {
        return Carbon::parse($this->timestamp / 1000, new DateTimeZone('UTC'))
            ->timezone(new DateTimeZone(Bitvavo::getTimezone()));
    }

    /** market() is a special helper method, designed to make
     * our code more expressive */
    public static function market(string|Market $market) : Builder
    {
        /** @var \Bitvavo\Builder $builder */
        $builder = static::query();
        $builder->where('market', $market->__toString());

        return $builder;
    }
}
