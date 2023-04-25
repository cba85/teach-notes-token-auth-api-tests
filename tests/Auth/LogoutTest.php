<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class LogoutTest extends TestCase
{
    public function testLogoutWithoutToken()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        try {
            $client->post('/api/auth/logout');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(401, $e->getCode());
        }
    }

    public function testLogoutSuccess()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        $response = $client->post('/api/auth/logout');

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testLogoutWithInvalidToken()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        try {
            $client->post('/api/auth/logout');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(401, $e->getCode());
        }
    }
}
