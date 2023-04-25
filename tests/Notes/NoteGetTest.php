<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class NoteGetTest extends TestCase
{
    public function testGetNoteThatDoesntExist()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        try {
            $client->get('/api/notes/3456789');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testGetAuthorizedNote()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        $response = $client->get('/api/notes/1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertNotEmpty($data);
        $this->assertObjectHasProperty('created_at', $data);
        $this->assertObjectHasProperty('updated_at', $data);
        $this->assertObjectHasProperty('content', $data);
        $this->assertObjectHasProperty('user_id', $data);
        $this->assertEquals(1, $data->user_id);
        $this->assertObjectHasProperty('id', $data);
    }

    public function testGetUnauthorizedNote()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token2");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        try {
            $client->get('/api/notes/1');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(403, $e->getCode());
        }
    }
}
