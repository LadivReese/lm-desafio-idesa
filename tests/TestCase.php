<?php

namespace Tests;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Token de autenticación para las pruebas
     */
    protected $authToken;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Authenticate a test user and attach the Authorization header
     * for subsequent requests.
     *
     * @return string The generated Bearer token.
     */
    protected function authenticate(): string
    {
        $user = \App\Models\User::factory()->create([
            'password' => app('hash')->make('password')
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $data = $response->response->getData(true);

        if (!isset($data['token'])) {
            throw new \Exception('Authentication failed: ' . json_encode($data));
        }

        $this->authToken = $data['token'];

        return $this->authToken;
    }

    /**
     * Helper para hacer requests autenticadas
     */
    protected function authenticatedJson($method, $uri, array $data = [], array $headers = [])
    {
        if (!$this->authToken) {
            $this->authenticate();
        }

        return $this->json($method, $uri, $data, array_merge([
            'Authorization' => "Bearer {$this->authToken}"
        ], $headers));
    }
}
