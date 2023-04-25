<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class NoteIndexTest extends TestCase
{
    public function testEmptyNotes()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token2");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);
        $response = $client->get('/api/notes');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertEmpty($data);
    }

    public function testGetNotes()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        $response = $client->get('/api/notes');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertNotEmpty($data);
        $this->assertObjectHasProperty('created_at', $data[0]);
        $this->assertObjectHasProperty('updated_at', $data[0]);
        $this->assertObjectHasProperty('content', $data[0]);
        $this->assertObjectHasProperty('user_id', $data[0]);
        $this->assertEquals(1, $data[0]->user_id);
        $this->assertObjectHasProperty('id', $data[0]);
    }
}
