<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class RegisterTest extends TestCase
{
    public function testRegisterWithoutParameters()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        try {
            $client->post('/api/auth/register');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testRegisterWithInvalidEmail()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test',
            'email' => 'test',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];

        try {
            $client->post('/api/auth/register', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testRegisterWithInvalidPassword()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'azer',
            'token_name' => "Test"
        ];

        try {
            $client->post('/api/auth/register', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testRegisterUser1Success()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test 1',
            'email' => 'test1@test.com',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];
        $response = $client->post('/api/auth/register', ['form_params' => $request]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody());
        $this->assertIsObject($data);
        $this->assertObjectHasProperty('token', $data);
        $this->assertNotEmpty($data->token);

        // Save token for next tests using authentication
        //file_put_contents(__DIR__ . "/../../data/token1", $data->token);
    }

    public function testRegisterUser2Success()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test 2',
            'email' => 'test2@test.com',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];
        $response = $client->post('/api/auth/register', ['form_params' => $request]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody());
        $this->assertIsObject($data);
        $this->assertObjectHasProperty('token', $data);
        $this->assertNotEmpty($data->token);

        // Save token for next tests using authentication
        file_put_contents(__DIR__ . "/../../data/token2", $data->token);
    }

    public function testRegisterWithExistingEmail()
    {
        $client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);

        $request = [
            'name' => 'Test 1',
            'email' => 'test1@test.com',
            'password' => 'azertyuiop',
            'token_name' => "Test"
        ];

        try {
            $client->post('/api/auth/register', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(409, $e->getCode());
        }
    }
}
