<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class LoginTest extends TestCase
{
    public function testLoginWithoutParameters()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        try {
            $client->post('/api/auth/login');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testLoginWithInvalidEmail()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test',
            'email' => 'test',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];

        try {
            $client->post('/api/auth/login', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testLoginWithInvalidPassword()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'email' => 'test@test.com',
            'password' => 'azer',
            'token_name' => "Test"
        ];

        try {
            $client->post('/api/auth/login', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testLoginWithUserWhoDoesNotExist()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test Error',
            'email' => 'test999@test.com',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];

        try {
            $client->post('/api/auth/login', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(401, $e->getCode());
        }
    }

    public function testLoginSuccess()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'email' => 'test1@test.com',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];
        $response = $client->post('/api/auth/login', ['form_params' => $request]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody());
        $this->assertIsObject($data);
        $this->assertObjectHasProperty('token', $data);
        $this->assertNotEmpty($data->token);

        // Save token for next tests using authentication
        file_put_contents(__DIR__ . "/../../data/token1", $data->token);
    }
}
