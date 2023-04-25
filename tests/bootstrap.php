<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleHttp;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

// Reset API database
$client = new GuzzleHttp(['base_uri' => $_ENV['API_URL'], 'headers' => ['Accept' => 'application/json']]);
$client->delete('/api/reset');
