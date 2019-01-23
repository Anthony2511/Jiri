<?php

Route::post('register', 'Auth\RegisterController@register');

//protected routes
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::resource('students', 'API\StudentController')->except([ 'create' ]);
    Route::get('students/getStudent/{student}', 'API\StudentController@getStudent');
    
    Route::resource('users', 'API\UserController')->except([ 'create', 'show' ]);
    
    Route::resource('events', 'API\EventController')->except([ 'create' ]);
    
    Route::resource('scores', 'API\ScoreController')->except([ 'create' ]);
    
    // Membres du jury
    Route::get('jiri/searchmembersofjury', 'API\EventController@searchMembersOfJury');
    Route::post('events/{id}/jury/add', 'API\EventController@addMemberToJury');
    Route::get('events/{id}/jury/get', 'API\EventController@getMembersOfJuryList');
    Route::delete('events/{id}/jury/remove/{memberId}', 'API\EventController@removeMember');
    
    // Etudiants du jury
    Route::get('jiri/searchstudents', 'API\EventController@searchStudents');
    Route::post('events/{id}/students/add', 'API\EventController@addStudent');
    Route::get('events/{id}/students/get', 'API\EventController@getStudents');
    Route::delete('events/{id}/students/remove/{studentId}', 'API\EventController@removeStudent');
    // Configure projects for students
    Route::get('events/{id}/students/{student}/config');
    
    // Projets
    Route::resource('events/{id}/projects', 'API\ProjectController')->except(['create']);
    Route::get('events/{id}/projects/get/{project}', 'API\ProjectController@selectedProject');
   
    // Implementation
    Route::resource('events/{id}/implementations', 'API\ImplementationController')->except(['create']);
    Route::get('events/{id}/implementations/students/{student}', 'API\ImplementationController@getImplementations');
    // Get implementation for specific user score
    Route::get('events/{id}/implementations/students/score/{student}', 'API\ImplementationController@getImplementationsForSpecificUser');
    
    // Performance
    Route::post('events/{id}/students/performance', 'API\PerformanceController@store');
    Route::put('events/{id}/students/performance/{student}', 'API\PerformanceController@update');
    
});
