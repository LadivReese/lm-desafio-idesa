<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

final class StudentController extends Controller
{
    /**
     * List students with optional filters, sorting and pagination.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Filtrado por nombre
        if ($request->filled('name')) {

            $name = $request->query('name');
            $query->where('name', 'like', "%{$name}%");
        }

        // Filtrado por email
        if ($request->filled('email')) {

            $email = $request->query('email');
            $query->where('email', 'like', "%{$email}%");
        }

        // Filtrado por nationality
        if ($request->filled('nationality')) {
            $query->where('nationality', $request->query('nationality'));
        }

        // Ordenamiento
        $sort = $request->query('sort', 'id');
        $direction = $request->query('dir', 'asc');
        $query->orderBy($sort, $direction);

        // Paginación
        $perPage = (int) $request->query('per_page', 15);
        $result = $query->paginate($perPage);

        return response()->json($result);
    }

    /**
     * Show single student with enrolled students.
     */
    public function show(int $id)
    {
        $student = Student::with('courses')->find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        return response()->json(['data' => $student]);
    }

    /**
     * Create a new student.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:students,email',
            'birthdate' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
        ]);

        $student = Student::create($request->only(['name','email','birthdate','nationality']));

        return response()->json(['data' => $student], 201);
    }

    /**
     * Update an existing student.
     */
    public function update(int $id, Request $request)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        
        $this->validate($request, [
            'name' => 'sometimes|required|string|max:150',
            'email' => 'sometimes|required|email|unique:students,email,'.$id,
            'birthdate' => 'nullable|date',
        ]);

        $student->fill($request->only(['name','email','birthdate','nationality']));
        $student->save();

        return response()->json(['data' => $student]);
    }

    /**
     * Delete a student.
     */
    public function destroy(int $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $student->delete();

        return response()->json(null, 204);
    }
}
