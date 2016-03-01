<?php namespace Mirage\Admin\Http\Controllers;

use Aliukevicius\LaravelRbac\Services\PermissionService;
use Aliukevicius\LaravelRbac\Services\RoleService;
use Aliukevicius\LaravelRbac\Models\Permission;
use Aliukevicius\LaravelRbac\Http\Controllers\Controller;
use Mirage\Admin\Events\CreateMenuEvent;

class PermissionController extends Controller {

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $roleModel;

    /** @var Permission */
    protected $permissionModel;

    /** @var PermissionService */
    protected $permissionService;

    /** @var RoleService */
    protected $roleService;

    public function __construct(PermissionService $permissionService, Permission $permission, RoleService $roleService)
    {
        $this->roleModel = \App::make(\Config::get('laravel-rbac.roleModel'));
        $this->permissionModel = $permission;
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }

    public function index()
    {
		app('events')->listen('menu.breadcrumb',function(CreateMenuEvent $event){
			$event->add('Permissions',config('admin.rbac.routeUrlPrefix').'/permissions')->icon('fa fa-fw fa-exclamation-circle');
		});

        $roles = $this->roleModel->all();
        $permissions = $this->permissionService->getGroupedByControllerPermissions();

        $roleCount = count($roles);

        $rolePermissions = $this->roleService->getRolePermissions();;

        $activePermissions = $this->roleService->getRolePermissions();

        return view('permissions.index', compact(
            'roles',
            'permissions',
            'activePermissions',
            'roleCount',
            'rolePermissions'
        ));
    }

    public function updatePermissionList()
    {
        $this->permissionService->updatePermissionList();

        return \Redirect::to(\URL::action('\\' . \Config::get('laravel-rbac.permissionController') . '@index'));
    }

    public function savePermissions()
    {
        $permissions = request('permissions',[]);

        $this->permissionService->savePermissions($permissions);

        return \Redirect::to(\URL::action('\\' . \Config::get('laravel-rbac.permissionController') . '@index'));
    }
}