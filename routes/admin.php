<?php

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth'],
], function () {
    Route::resource('dashboard', 'DashboardController');
    Route::resource('department', 'DepartmentController');
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('teacher', 'TeacherController');
    Route::resource('schoolprogram', 'SchoolprogramController');
    Route::resource('gradelevel', 'GradelevelController');
    Route::resource('section', 'SectionController');
    Route::resource('subject', 'SubjectController');
    Route::resource('classday', 'ClassdayController');
    Route::resource('period', 'PeriodController');
    Route::resource('timeslot', 'TimeslotController');
    Route::get('edit-account-info', 'UserController@accountInfo')->name('admin.account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('admin.account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('admin.account.password.store');
});
