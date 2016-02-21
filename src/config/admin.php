<?php

return [
	// config for rbac
	'rbac' => [
		'routeUrlPrefix'            => 'admin', //route prefix for all package routes

		'rolesPerPage'              => 10, // how many roles to display in one page

		'routePermissionChecking'   => false, // change to false if route permission checking should be turned off

		'roleController'            => 'Mirage\Admin\Http\Controllers\RoleController',
		'roleModel'                 => 'Aliukevicius\LaravelRbac\Models\Role',
		'permissionController'      => 'Mirage\Admin\Http\Controllers\PermissionController',

		// class which is available through ActiveUser facade
		'activeUserService'         => 'Aliukevicius\LaravelRbac\Services\ActiveUserService',

		// class for global "checkPermission" middleware
		'checkPermissionMiddleware' => 'Aliukevicius\LaravelRbac\Http\Middleware\CheckPermission'
	],

	// config for theme
	'theme' => [
		'basePath'	=> resource_path('themes'),
		'themes'	=>	[
			'admin'	=>	['bootstrap','jquerymobile'],
			'frontend' => ['bootstrap','flat','foundation']
		],
		'current_theme' => [
			'admin' => 'bootstrap',
			'frontend' => 'foundation'
		],
		'current_group'=>'admin'
	],

	// config for module
	'module' => [
		'modules' => [
	//		 'module1' => true
		],
		'basePath' => app_path('Modules'),
		'baseNamespace' =>'App\\Modules\\'
	]
];
