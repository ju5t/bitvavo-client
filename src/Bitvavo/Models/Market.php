<?php

namespace Bitvavo\Models;

class Market extends Model
{
    protected static $endpoint = 'markets';

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

    public function __toString() : string
    {
        if (empty($this->market)) {
            return $this->base.'-'.$this->quote;
        }

        return $this->market;
    }
}
