<?php
declare(strict_types=1);

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

final class EnrollmentTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_enroll_student_in_course(): void
    {
        $this->authenticate();

        $student = Student::create([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'birthdate' => '2002-05-10',
            'nationality' => 'Paraguayan',
        ]);

        $course = Course::create([
            'title' => 'Laravel Básico',
            'description' => 'Intro a Laravel',
            'start_date' => '06-10-2025',
            'end_date' => '31-10-2025',
        ]);

        $this->authenticatedJson('POST', '/api/enrollments/create', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->seeStatusCode(201)
             ->seeJsonContains(['student_id' => $student->id]);
    }

    public function test_can_list_courses_of_student(): void
    {
        $this->authenticate();

        $student = Student::factory()->create();
        $course = Course::factory()->create();

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->authenticatedJson("GET", "/api/enrollments/lists?student_id={$student->id}");

        $this->seeStatusCode(200)
             ->seeJsonStructure(['data']);
    }
}
