<?php

namespace Bitvavo;

use Carbon\Carbon;
use League\Csv\Writer;
use SplTempFileObject;
use Bitvavo\Interfaces\API;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Bitvavo\Exceptions\BitvavoResponseException;

class Bitvavo extends App implements API
{
    private Factory $factory;

    private static $timezone = 'Europe/Amsterdam';

    public function __construct(
        public string $apiKey,
        public string $apiSecret,
        public string $restUrl = 'https://api.bitvavo.com/v2',
        public int $accessWindow = 60000,
    )
    {
        $this->factory = new Factory();

        Collection::macro('csv', function () {
            /** @var Collection $this */
            $csv = Writer::createFromFileObject(new SplTempFileObject);
            $csv->insertOne(array_keys($this->first()->toArray()));
            $csv->insertAll($this->toArray());
            $csv->output();
        });
    }

    public function time()
    {
        return $this->factory->get($this->restUrl.'/time');
    }

    public function get(string $endpoint, array $params = [])
    {
        return $this->call('get', $endpoint, $params);
    }

    public function post(string $endpoint, array $params = [], array $body = [])
    {
        // Not implemented yet.
    }

    private function call(string $method, string $endpoint, array $params = [], array $body = [])
    {
        $url = $this->restUrl.'/'.$endpoint;
        $method = strtoupper($method);
        $timestamp = $this->createTimestamp();

        $signature = Signature::make(
            endpoint: $endpoint,
            timestamp: $timestamp,
            method: $method,
            params: $params,
            body: $body,
            secret: $this->apiSecret,
        );

        $headers = [
            'Bitvavo-Access-Key' => $this->apiKey,
            'Bitvavo-Access-Signature' => $signature,
            'Bitvavo-Access-Timestamp' => $timestamp,
            'Bitvavo-Access-Window' => $this->accessWindow,
            'Content-Type' => 'application/json',
        ];

        if ($method === 'GET') {
            return $this->processResponse(
                $this->factory->withHeaders($headers)->$method($url, $params)
            );
        }

        return $this->processResponse(
            $this->factory->withHeaders($headers)->$method($url, $params, $body)
        );
    }

    public static function getTimezone() : string
    {
        return static::$timezone;
    }

    public static function setTimezone(string $timezone) : void
    {
        static::$timezone = $timezone;
    }

    private function processResponse(Response $response)
    {
        if ($errorCode = $response->json('errorCode')) {
            throw new BitvavoResponseException(
                'Error code '.$errorCode.': '.$response->json('error')
            );
        }

        return $response->json();
    }

    private function createTimestamp()
    {
        return Carbon::now()->timestamp * 1000;
    }
}
