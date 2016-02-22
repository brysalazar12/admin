<?php
Route::group(['prefix' => Config::get('laravel-rbac.routeUrlPrefix'), 'middleware' => ['web','checkPermission','adminAuth']], function(){
    Route::resource('roles', Config::Get('laravel-rbac.roleController'));

    Route::get('permissions', [
        'as' => 'permissions.index',
        'uses' => Config::Get('laravel-rbac.permissionController') . '@index'
    ]);

    Route::get('permissions/update-permission-list', [
        'as' => 'permissions.updatePermissionList',
        'uses' => Config::Get('laravel-rbac.permissionController') . '@updatePermissionList'
    ]);

    Route::post('permissions/save-permissions', [
        'as' => 'permissions.savePermissions',
        'uses' => Config::Get('laravel-rbac.permissionController') . '@savePermissions'
    ]);

	Route::get('dashboard',[
		'as' => 'dashboard.index',
		'uses' => 'Mirage\Admin\Http\Controllers\DashboardController@index'
	]);
});

Route::group(['prefix' => Config::get('laravel-rbac.routeUrlPrefix'),'middleware'=>['web']],function(){
	Route::get('login','Mirage\Admin\Http\Controllers\AuthController@showLoginForm');
	Route::post('login','Mirage\Admin\Http\Controllers\AuthController@login');
	Route::get('logout','Mirage\Admin\Http\Controllers\AuthController@logout');

	Route::get('register','Mirage\Admin\Http\Controllers\AuthController@showRegistrationForm');
	Route::post('register','Mirage\Admin\Http\Controllers\AuthController@register');
});
