<?php namespace Mirage\Admin\Http\Controllers;

use Aliukevicius\LaravelRbac\Http\Controllers\Controller;
use Aliukevicius\LaravelRbac\Http\Requests\CreateRoleRequest;
use Aliukevicius\LaravelRbac\Http\Requests\EditRoleRequest;
use Mirage\Admin\Events\CreateMenuEvent;

/**
 * Description of RoleController
 *
 * @author Bryan Salazar
 */
class RoleController extends Controller
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = \App::make(\Config::get('laravel-rbac.roleModel'));
    }

    public function index()
    {
		app('events')->listen('menu.breadcrumb',function(CreateMenuEvent $event){
			$event->add('Roles',config('admin.rbac.routeUrlPrefix').'/roles')->icon('fa fa-group');
		});

        $perPage = \Config::get('laravel-rbac.rolesPerPage', 10);

        $list = $this->roleModel->paginate($perPage);

        return view('roles.index', compact('list', 'urlPart'));
    }

    public function create()
    {
		app('events')->listen('menu.breadcrumb',function(CreateMenuEvent $event){
			$event->add('Roles',config('admin.rbac.routeUrlPrefix').'/roles')->icon('fa fa-group')->data('order',1);
			$event->add('Add Role',config('admin.rbac.routeUrlPrefix').'/roles/create')->icon('fa fa-plus')->data('order',2);
		});
        $formAction = $this->getRoleUrl('store');

        return view('roles.create', compact('formAction'));
    }

    public function store(CreateRoleRequest $request)
    {
        $data = $request->only(['name', 'description']);

        $this->roleModel->create($data);

        $this->setStatusMessage(trans('aliukevicius/laravelRbac::lang.role.messageCreated', ['name' => $data['name']]));

        return \Redirect::to($this->getRoleUrl('index'));
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
		$name = $role->name;
		app('events')->listen('menu.breadcrumb',function(CreateMenuEvent $event) use($id, $name){
			$event->add('Roles',config('admin.rbac.routeUrlPrefix').'/roles')->icon('fa fa-group')->data('order',1);
			$event->add('Edit Role '.$name,config('admin.rbac.routeUrlPrefix').'/roles/' . $id . '/edit')->icon('fa fa-plus')->data('order',2);
		});

        $formAction = $this->getRoleUrl('update', ['roles' => $id]);

        return view('roles.edit', compact('formAction', 'role'));
    }

    public function update(EditRoleRequest $request, $id)
    {
        $role = $this->roleModel->find($id);

        $data = $request->only(['name', 'description']);
        $role->update($data);

        $this->setStatusMessage(trans('aliukevicius/laravelRbac::lang.role.messageUpdated', ['name' => $data['name']]));

        return \Redirect::to($this->getRoleUrl('index'));
    }

    public function destroy($id)
    {
        $this->roleModel->destroy($id);

        $this->setStatusMessage(trans('aliukevicius/laravelRbac::lang.role.messageDeleted'));

        return \Redirect::to($this->getRoleUrl('index'));
    }

    /**
     * Get URL by role controller action
     *
     * @param       $actionName
     * @param array $parameters
     * @return string
     */
    protected function getRoleUrl($actionName, $parameters = [])
    {
        return \URL::action('\\' . \Config::get('laravel-rbac.roleController') . '@' . $actionName, $parameters);
    }
}
