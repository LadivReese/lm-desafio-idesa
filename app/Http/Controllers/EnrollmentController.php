<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

final class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::query()->with(['student','course']);

        if ($request->filled('student_id')) {
            $query->where('student_id', (int)$request->query('student_id'));
        }
        if ($request->filled('course_id')) {
            $query->where('course_id', (int)$request->query('course_id'));
        }

        $perPage = (int)$request->query('per_page', 15);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'required|integer|exists:students,id',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $studentId = (int)$request->input('student_id');
        $courseId = (int)$request->input('course_id');

        // prevenir duplicados
        $exists = Enrollment::where('student_id', $studentId)->where('course_id', $courseId)->first();
        if ($exists) {
            return response()->json(['error' => 'Student already enrolled in this course'], 422);
        }

        $enrollment = Enrollment::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enrolled_at' => Carbon::now()->toDateTimeString(),
        ]);

        return response()->json(['data' => $enrollment], 201);
    }

    public function destroy(int $id)
    {
        $enrollment = Enrollment::find($id);
        if (!$enrollment) {
            return response()->json(['error' => 'Enrollment not found'], 404);
        }
        $enrollment->delete();
        return response()->json(null, 204);
    }
}
