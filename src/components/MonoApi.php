<?php

namespace Src\Components;

use App\Dto\Transaction;
use GuzzleHttp\Client;

class MonoApi
{
    private const API_LINK = 'https://api.monobank.ua';
    private const API_TOKEN = 'uFrZl1nZCrqWKXlUX90X-YfVCiqAcHTt1qYUZ_YfylTw';
    private const API_METHOD_CLIENT_INFO = '/personal/client-info';
    private const API_METHOD_STATEMENT = '/personal/statement';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            [
                'headers' => [
                    'X-Token' => self::API_TOKEN
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
        $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $transactions = [];
        foreach ($response as $item) {
            $transactions[] = new Transaction($item);
        }
        return $transactions;
    }
}
