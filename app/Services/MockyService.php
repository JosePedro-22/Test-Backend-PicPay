<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MockyService
{
    /**
     * @var Client
     */
    public function __construct(
        private readonly Client $client
    ){}

    public function authorizeTransaction(): array
    {
        $uri = 'v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
        try {
            $response = $this->client->request('GET', $uri);
            dd($response);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return ['message' => 'Não Autorizado'];
        }
    }

    public function notifyUser(string $fakeUserId): array
    {
        $uri = 'v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';
        try {
            $response = $this->client->request('GET', $uri);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return ['message' => 'deu beyblade'];
        }
    }
}
