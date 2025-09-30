<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group([
    'prefix' => 'api'
], function () use ($router) {

    // Auth
    $router->post('auth/register', 'AuthController@register');
    $router->post('auth/login', 'AuthController@login');

    // Protected routes (middleware 'auth')
    $router->group(['middleware' => 'auth'], function () use ($router) {
        // Students
        $router->get('students/lists', 'StudentController@index');
        $router->get('students/{id:[0-9]+}', 'StudentController@show');
        $router->post('students/create', 'StudentController@store');
        $router->put('students/update/{id:[0-9]+}', 'StudentController@update');
        $router->delete('students/delete/{id:[0-9]+}', 'StudentController@destroy');

        // Courses
        $router->get('courses/lists', 'CourseController@index');
        $router->get('courses/{id:[0-9]+}', 'CourseController@show');
        $router->post('courses/create', 'CourseController@store');
        $router->put('courses/update/{id:[0-9]+}', 'CourseController@update');
        $router->delete('courses/delete/{id:[0-9]+}', 'CourseController@destroy');

        // Enrollments
        $router->post('enrollments/create', 'EnrollmentController@store');
        $router->get('enrollments/lists', 'EnrollmentController@index'); // supports ?student_id or ?course_id
        $router->delete('enrollments/delete/{id:[0-9]+}', 'EnrollmentController@destroy');
    });
});