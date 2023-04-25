<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class NoteCreateTest extends TestCase
{
    public function testWrongParameterForCreation()
    {
        $token = file_get_contents(__DIR__ . "/../../data/token1");

        $client = new GuzzleHttp([
            'base_uri' => $_ENV['API_URL'],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ]
        ], ['form_params' => []]);

        try {
            $client->post('/api/notes');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testCreateANoteWithUser1()
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
            'content' => "Nouvelle note de l'utilisateur 1"
        ];

        $response = $client->post('/api/notes', ['form_params' => $request]);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertObjectHasProperty('created_at', $data);
        $this->assertObjectHasProperty('updated_at', $data);
        $this->assertObjectHasProperty('content', $data);
        $this->assertEquals($request['content'], $data->content);
        $this->assertObjectHasProperty('user_id', $data);
        $this->assertEquals(1, $data->user_id);
    }
}
