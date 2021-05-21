<?php

namespace Bitvavo\Models;

use Bitvavo\Bitvavo;

class Market extends Model
{
    protected $fillable = [
        'base',
        'quote',
    ];

    protected $guarded = [
        'market',
        'status',
        'pricePrecision',
        'minOrderInBaseAsset',
        'minOrderInQuoteAsset',
    ];

    public static function find(string $market) : Market
    {
        $api = Bitvavo::resolve(Bitvavo::class);

        $params['market'] = $market;
        $response = $api->get('markets', $params);

        return static::unguarded(
            function () use ($response) {
                return static::make(...$response);
            }
        );
    }

    public static function all()
    {
        /** @var \Bitvavo\Bitvavo $api */
        $api = Bitvavo::resolve(Bitvavo::class); // Inception...

        $response = $api->get('markets');
        return static::asCollection($response);
    }

    public function __toString() : string
    {
        if (empty($this->market)) {
            return $this->base.'-'.$this->quote;
        }

        return $this->market;
    }
}
