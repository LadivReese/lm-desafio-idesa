<?php
declare(strict_types=1);

namespace Tests;

use App\Models\Course;
use Laravel\Lumen\Testing\DatabaseMigrations;

final class CourseTest extends TestCase
{
    use DatabaseMigrations;

    
    public function test_can_create_course(): void
    {
        $this->authenticate();

        $payload = [
            'title' => 'PHP Avanzado',
            'description' => 'Curso de PHP 8.2 con Lumen',
            'start_date' => '06-10-2025',
            'end_date' => '31-10-2025',
        ];

        $this->authenticatedJson('POST', '/api/courses/create', $payload);

        $this->seeStatusCode(201)
             ->seeJsonContains(['title' => 'PHP Avanzado']);
    }

    public function test_can_list_courses(): void
    {
        $this->authenticate();

        Course::factory()->count(2)->create();

        $this->authenticatedJson('GET', '/api/courses/lists');

        $this->seeStatusCode(200)
             ->seeJsonStructure(['data']);
    }
}
