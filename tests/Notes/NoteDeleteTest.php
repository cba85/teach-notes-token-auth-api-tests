<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class NoteDeleteTest extends TestCase
{
    public function testDeleteANoteThatDoesNotExist()
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
            $client->delete('/api/notes/9999');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testDeleteAnUnauthorizedNote()
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
            $client->delete('/api/notes/1');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(403, $e->getCode());
        }
    }

    public function testDeleteANoteWithUser1()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);
        $response =  $client->delete('/api/notes/1');

        $this->assertEquals(204, $response->getStatusCode());
        $data = json_decode($response->getBody());
        $this->assertEmpty($data);
    }
}
