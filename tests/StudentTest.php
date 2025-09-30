<?php
declare(strict_types=1);

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Student;

final class StudentTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_student(): void
    {
        $this->authenticate();

        $payload = [
            'name' => 'Juan Perez',
            'email' => 'juan@example.com',
            'birthdate' => '31-10-2025',
            'nationality' => 'Paraguayan'
        ];

        $this->authenticatedJson('POST', '/api/students/create', $payload);

        $this->seeStatusCode(201)
             ->seeJsonContains(['email' => 'juan@example.com']);
    }

    public function test_can_list_students(): void
    {
        $this->authenticate();

        Student::factory()->count(3)->create();

        $this->authenticatedJson('GET', '/api/students/lists');

        $this->seeStatusCode(200)
             ->seeJsonStructure(['data']);
    }
}
