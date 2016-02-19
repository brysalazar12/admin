<?php namespace Mirage\Admin;

use Aliukevicius\LaravelRbac\RbacServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Mirage\Admin\Events\CreateMenuEvent;
use Mirage\Admin\Ext\Menu\Menu;
use Mirage\Admin\Http\Middleware\AdminAfterMiddleware;
//use Lavary\Menu\Collection;
//use Illuminate\Support\ServiceProvider;

/**
 * Description of AdminServiceProvider
 *
 * @author Bryan Salazar
 */
class AdminServiceProvider extends RbacServiceProvider
{
	protected $providers = [
		\Mirage\ThemeManager\ThemeManagerServiceProvider::class,
		\Mirage\ModuleManager\ModuleManagerServiceProvider::class,
		\Lavary\Menu\ServiceProvider::class,
	];

	protected $facades = [
		'Module'		=> \Mirage\ModuleManager\Facades\ModuleFacade::class,
		'Theme'			=> \Mirage\ThemeManager\Facades\ThemeFacade::class,
		'Menu'			=> \Lavary\Menu\Facade::class,
		'ActiveUser'	=> \Aliukevicius\LaravelRbac\Facades\ActiveUser::class,
	];

	public function boot()
	{
		$basePath = __DIR__ . '/';
        $this->mergeConfigFrom($basePath . 'config/laravel-rbac.php', 'laravel-rbac');
		$this->loadTranslationsFrom($basePath . 'resources/lang', 'aliukevicius/laravelRbac');

        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app->make('Illuminate\Routing\Router');

        // Register global checkPermission middleware
        $router->middleware('checkPermission', $this->app['config']->get('laravel-rbac.checkPermissionMiddleware'));

        $this->publishes([
            $basePath . 'config/laravel-rbac.php' => config_path('laravel-rbac.php'),
			$basePath . 'resources/themes' => resource_path('themes')
        ]);

        // get package routes
        require_once $basePath . 'Http/routes.php';

//		$router = $this->app['router'];
//		$router->middleware('adminAfter', AdminAfterMiddleware::class);

		$dispatcher = app('events');
		$this->defaultLeftMenu($dispatcher);
		$this->createLeftMenu();
		$this->createBreadcrumb($dispatcher);
	}

	public function register()
	{
		parent::register();
		$this->registerServiceProviders();
		$this->app->singleton('menu', function($app) {
		 	return new Menu();
		});
	}

	protected function registerServiceProviders()
	{
		foreach($this->providers as $provider) {
			$this->app->register($provider);
		}

		AliasLoader::getInstance($this->facades);
	}

	protected function createLeftMenu()
	{
		\Menu::make('leftMenu',function($menu){
			$event = new CreateMenuEvent($menu);
			event('menu.left',$event);
		})->sortBy('order');
	}

	protected function createBreadcrumb($dispatcher)
	{
		$dispatcher->listen('composing: *', function(){
			\Menu::make('breadcrumb',function($menu){
				$event = new CreateMenuEvent($menu);
				event('menu.breadcrumb',$event);
			})->sortBy('order');
		});
	}

	protected function defaultLeftMenu($dispatcher)
	{
		$dispatcher->listen('menu.left',function(CreateMenuEvent $event){
			$event->add('Dashboard','admin/dashboard')->icon('fa fa-fw fa-dashboard')->data('order',10);
			$settings = $event->add('Settings')->icon('fa fa-gear')->data('order',20)->data('target','settings');
			$settings->link->attr(['href'=>'javascript:;','data-target'=>'#settings','data-toggle'=>'collapse','class'=>'collapsed']);
			$settings->add('Roles','admin/roles')->icon('fa fa-group')->data('order',1);
			$addRole = $settings->add('Add Role','admin/roles/create')->icon('fa fa-plus')->data('order',2);
//			$addRole->divide();
			$settings->add('Permissions','admin/permissions')->icon('fa fa-exclamation-circle')->data('order',3);

		});
	}
}
