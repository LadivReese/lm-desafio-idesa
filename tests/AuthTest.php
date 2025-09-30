<?php
declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

final class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_register(): void
    {
        $payload = [
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ];

        $this->post('/api/auth/register', $payload);

        $this->seeStatusCode(201)
             ->seeJsonContains(['email' => 'admin@example.com']);
    }

    public function test_user_can_login(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'login@example.com',
            'password' => app('hash')->make('secret123'),
        ]);

        $this->post('/api/auth/login', [
            'email' => 'login@example.com',
            'password' => 'secret123',
        ]);

        $this->seeStatusCode(200)
             ->seeJsonStructure(['token']);
    }
}
