<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index');


// Route::get('/grades','HomeController@generategrades');

Route::post('/normalize','HomeController@normalize');

Route::post('/register', 'Auth\RegisterController@create');

Route::post('/course/addparam/{id}', ['as' => 'course.param', 'uses' => 'CourseController@addparam']);

Route::get('/createcourse', ['as' => 'course.create', 'uses' => 'CourseController@create']);

Route::get('/course/{id}/upload', ['as' => 'course.upload', 'uses' => 'StaffController@upload']);

Route::get('course/{stream}/{id}', ['as' => 'course.show', 'uses' => 'CourseController@show']);

Route::get('course/{id}', ['as' => 'course.edit', 'uses' => 'CourseController@edit']);

Route::post('upload/{id}/marks', ['as' => 'upload.marks', 'uses' => 'StaffController@upload_marks']);

Route::get('/course/{stream}/{id}/marks',['as' => 'course.marks', 'uses' => 'CourseController@getmarks']);

Route::get('/course/{stream}/{id}/grades',['as' => 'course.grades', 'uses' => 'StaffController@grades']);

Route::get('/course/{stream}/{id}/report',['as' => 'course.report', 'uses' => 'StaffController@report']);

Route::get('/{id}/report',['as' => 'course.overreport', 'uses' => 'StaffController@overreport']);

Route::get('/course/{id}/{course_id}/scores',['as' => 'course.score', 'uses' => 'StudentController@getmarks']);

Route::get('/grade/courses', ['as' => 'grades.create', 'uses' => 'AdminController@generategrades']);

Route::get('/grade/{id}', ['as' => 'grades.show', 'uses' => 'AdminController@getgrades']);

Route::get('/normalize/{id}', ['as' => 'grades.normalize', 'uses' => 'AdminController@normalize']);

Route::get('/admin/courses', ['as' => 'admin.courses', 'uses' => 'AdminController@getmarks']);

Route::get('/admin/marks/{id}', ['as' => 'admin.marks', 'uses' => 'AdminController@marks']);