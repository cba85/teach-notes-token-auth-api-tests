<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class NoteUpdateTest extends TestCase
{

    public function testWrongParameterForUpdate()
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
            $client->post('/api/notes');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testUpdateANoteThatDoesNotExist()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        $request = [
            'content' => "Note mise à jour de l'utilisateur 1"
        ];

        try {
            $client->put('/api/notes/9999', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testUpdateAUnauthorizedNote()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token2");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        $request = [
            'content' => "Note mise à jour de l'utilisateur 1"
        ];

        try {
            $client->put('/api/notes/1', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(403, $e->getCode());
        }
    }

    public function testUpdateANoteWithUser1()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ]);

        $request = [
            'content' => "Note mise à jour de l'utilisateur 1"
        ];

        $response = $client->put('/api/notes/1', ['form_params' => $request]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertNotEmpty($data);
        $this->assertObjectHasProperty('created_at', $data);
        $this->assertObjectHasProperty('updated_at', $data);
        $this->assertObjectHasProperty('content', $data);
        $this->assertEquals($request['content'], $data->content);
        $this->assertObjectHasProperty('user_id', $data);
        $this->assertEquals(1, $data->user_id);
        $this->assertObjectHasProperty('id', $data);
    }
}
