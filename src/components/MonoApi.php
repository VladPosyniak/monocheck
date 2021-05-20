<?php

namespace Src\Components;

use GuzzleHttp\Client;

class MonoApi
{
    private const API_LINK = 'https://api.monobank.ua';
    private const API_METHOD_CLIENT_INFO = '/personal/client-info';
    private const API_METHOD_STATEMENT = '/personal/statement';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            [
                'headers' => [
                    'X-Token' => env('MONOBANK_API_TOKEN')
                ]
            ]
        );
    }

    public function getClientInfo(): array
    {
        $response = $this->client->request('GET', self::API_LINK . self::API_METHOD_CLIENT_INFO)->getBody()->getContents();

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getStatement(int $from, int $to): array
    {
        $response = $this->client->request('GET', self::API_LINK . self::API_METHOD_STATEMENT . '/0/' . $from . '/' . $to);
        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
