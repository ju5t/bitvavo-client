<?php

namespace Bitvavo\Models;

use DateTimeZone;
use Carbon\Carbon;
use Bitvavo\Bitvavo;
use Bitvavo\Exceptions\BitvavoClientException;
use Illuminate\Support\Collection;

class Trade extends Model
{
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
        'settled' => 'boolean'
    ];

    protected $appends = [
        'date',
    ];

    public function getDateAttribute()
    {
        return Carbon::parse($this->timestamp / 1000, new DateTimeZone('UTC'))
            ->timezone(Bitvavo::getTimezone());
    }

    public static function market(string|Market $market) : Trade
    {
        $builder = static::query();

        $builder->unguarded(function () use ($builder, $market) {
            $builder->setAttribute('market', $market->__toString());
        });

        return $builder;
    }

    public static function all(?string $market = null) : Collection
    {
        /** @var \Bitvavo\Models\Trade $builder */
        $builder = static::query();

        $params = [
            'market' => $builder->getMarket(market: $market)
        ];

        /** @var \Bitvavo\Bitvavo $api */
        $api = Bitvavo::resolve(Bitvavo::class); // Inception...

        $response = $api->get(endpoint: 'trades', params: $params);
        return static::asCollection($response);
    }

    private function getMarket(?string $market)
    {
        if (empty($this->market) && empty($market)) {
            throw new BitvavoClientException('Market not set');
        }

        return $market ?? $this->market;
    }
}
