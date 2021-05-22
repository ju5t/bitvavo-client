<?php

namespace Bitvavo\Models;

class Order extends Model
{
    protected static $endpoint = 'orders';

    protected $guarded = [
        'orderId',
        'market',
        'created',
        'updated',
        'status',
        'side',
        'orderType',
        'amount',
        'amountRemaining',
        'price',
        'amountQuote',
        'amountQuoteRemaining',
        'onHold',
        'onHoldCurrency',
        'triggerPrice',
        'triggerAmount',
        'triggerType',
        'triggerReference',
        'filledAmount',
        'filledAmountQuote',
        'feePaid',
        'feeCurrency',
        'fills',
        'selfTradePrevention',
        'visible',
        'timeInForce',
        'postOnly',
        'disableMarketProtection',
    ];
}
