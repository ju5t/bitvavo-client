<?php

namespace Bitvavo;

use Bitvavo\Exceptions\NoSecretFoundException;

class Signature
{
    public static function make(
        string $endpoint,
        string $timestamp,
        string $method,
        array $params = [],
        array $body = [],
        string $secret = null,
    ) : string
    {
        if (empty($secret)) {
            throw new NoSecretFoundException();
        }

        $query = http_build_query($params);
        $url = '/'.$endpoint.(! empty($params) ? '?'.$query : null);

        $hashString = (string) $timestamp.strtoupper($method).'/v2'.$url;

        if (!empty($body)) {
            $hashString .= json_encode($body, JSON_THROW_ON_ERROR);
        }

        return hash_hmac('sha256', $hashString, $secret);
    }
}
