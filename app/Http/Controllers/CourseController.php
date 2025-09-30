<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

final class CourseController extends Controller
{
    /**
     * List courses with optional filters, sorting and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::query();

        // búsqueda por título
        if ($request->filled('title')) {
            $title = $request->query('title');
            $query->where('title', 'like', "%{$title}%");
        }

        // búsqueda por descripcion
        if ($request->filled('description')) {
            $description = $request->query('description');
            $query->Where('description', 'like', "%{$description}%");
        }

        // filtros por rango de fechas
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->query('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->query('end_date'));
        }

        // ordenamiento seguro
        $allowedSorts = ['id', 'title', 'start_date', 'end_date', 'created_at'];
        $sort = in_array($request->query('sort', 'id'), $allowedSorts, true) ? $request->query('sort', 'id') : 'id';
        $direction = strtolower($request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $direction);

        $perPage = (int) $request->query('per_page', 15);
        $result = $query->paginate($perPage);

        return response()->json($result);
    }

    /**
     * Show single course with enrolled students.
     */
    public function show(int $id): JsonResponse
    {
        $course = Course::with('students')->find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json(['data' => $course]);
    }

    /**
     * Create a new course.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $course = Course::create($request->only(['title', 'description', 'start_date', 'end_date']));

        return response()->json(['data' => $course], 201);
    }

    /**
     * Update an existing course.
     *
     * Nota: para validar que end_date >= start_date también cuando
     * no se envía start_date en la request, armamos los datos usando
     * el start_date actual del curso como fallback.
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $input = $request->only(['title', 'description', 'start_date', 'end_date']);

        // Usamos el start_date existente si no se proporciona en la request
        $validationData = array_merge($input, [
            'start_date' => $input['start_date'] ?? $course->start_date,
        ]);

        $validator = Validator::make($validationData, [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course->fill($input);
        $course->save();

        return response()->json(['data' => $course]);
    }

    /**
     * Delete a course (cascades to enrollments per migration).
     */
    public function destroy(int $id): JsonResponse
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(null, 204);
    }
}
