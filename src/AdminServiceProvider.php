<?php namespace Mirage\Admin;

use Aliukevicius\LaravelRbac\RbacServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Mirage\Admin\Events\CreateMenuEvent;
use Mirage\Admin\Ext\Menu\Menu;
use Mirage\Admin\Http\Middleware\AdminAfterMiddleware;
use Mirage\ThemeManager\Helpers\Theme;
use Mirage\ThemeManager\Helpers\Contracts\ThemeContract;
use Illuminate\View\FileViewFinder;
use Mirage\ModuleManager\Contracts\ModuleManagerContract;
use Mirage\ModuleManager\ModuleManager;
use Mirage\ModuleManager\Events\InstallModuleEvent;
use Illuminate\Support\ServiceProvider;
use Mirage\Admin\Http\Middleware\AdminAuthenticate;

/**
 * Description of AdminServiceProvider
 *
 * @author Bryan Salazar
 */
class AdminServiceProvider extends ServiceProvider
{
	protected $providers = [
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

		$this->mapRBACConfig();

		$this->loadTranslationsFrom($basePath . 'resources/lang', 'aliukevicius/laravelRbac');

		$this->bootModuleManager();

        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app->make('Illuminate\Routing\Router');

        // Register global checkPermission middleware
        $router->middleware('checkPermission', $this->app['config']->get('laravel-rbac.checkPermissionMiddleware'));

        $this->publishes([
			$basePath . 'config/admin.php' => config_path('admin.php'),
			resource_path('themes/admin/bootstrap/assets') => public_path('themes/admin/startbootstrap-sb-admin/assets'),
//			$basePath . 'resources/themes' => resource_path('themes'),
//			$basePath . 'resources/themes/admin/bootstrap/assets' => public_path('themes/admin/startbootstrap-sb-admin/assets')
        ]);

        // get package routes
        require_once $basePath . 'Http/routes.php';

		// create all menus
		$dispatcher = app('events');
//		$dispatcher->listen('Illuminate\Auth\Events\Login',function() use($dispatcher) {
			$this->adminOnly($dispatcher);
//		});
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/admin.php', 'admin');

		$this->registerServiceProviders();
		$this->app->singleton('menu', function($app) {
		 	return new Menu();
		});

		$this->registerRBACManager();
		$this->registerModuleManager();
		$this->registerThemeManager();
		$this->registerAdminCommands();

		$router = $this->app['router'];
		$router->middleware('adminAuth', AdminAuthenticate::class);

	}

	protected function adminOnly($dispatcher)
	{
		$this->defaultLeftMenu($dispatcher);
		$this->defaultTopMenu($dispatcher);
		$this->createLeftMenu($dispatcher);
		$this->createTopMenu($dispatcher);
		$this->createBreadcrumb($dispatcher);
	}

	protected function registerAdminCommands()
	{
		$this->app['command.admin.install'] = $this->app->share(function($app){
			return $app['Mirage\Admin\Console\AdminInstallCommand'];
		});
		$this->commands(['command.admin.install']);
	}

	protected function mapRBACConfig()
	{
		config(['laravel-rbac'=>config('admin.rbac')]);
	}

	protected function registerRBACManager()
	{
        $this->app['command.laravel-rbac.create-migrations'] = $this->app->share(
            function ($app) {
                return $app['Aliukevicius\LaravelRbac\Console\Commands\CreateMigrationsCommand'];
            }
        );

        $this->app['command.laravel-rbac.update-permission-list'] = $this->app->share(
            function ($app) {
                return $app['Aliukevicius\LaravelRbac\Console\Commands\UpdatePermissionListCommand'];
            }
        );

        $this->app->singleton('Aliukevicius\LaravelRbac\ActiveUser', function($app){

            return $app->make($this->app['config']->get('laravel-rbac.activeUserService'));
        });

        $this->app['facade.laravel-rbac.active-user'] = $this->app->share(function($app)
        {
            return $app->make('Aliukevicius\LaravelRbac\ActiveUser');
        });

        $this->commands(['command.laravel-rbac.create-migrations', 'command.laravel-rbac.update-permission-list']);
	}

	protected function registerThemeManager()
	{
		$this->app->singleton('theme',function($app){
			return $app->make(ThemeContract::class);
		});

		$this->app->singleton(ThemeContract::class, function($app){
			return new Theme($app);
		});

		$this->registerViewFinder();
	}

	protected function registerModuleManager()
	{
		$this->app->singleton(ModuleManagerContract::class, function($app){
			return new ModuleManager($this->app);
		});

		$modules = config('admin.module.modules');

		$moduleManager = app(ModuleManagerContract::class);
		$moduleManager->setModuleBasePath(config('admin.module.basePath'));
		$moduleManager->setBaseNamespace(config('admin.module.baseNamespace'));

		foreach($modules as $moduleName => $isEnabled) {
			$moduleManager->loadModule($moduleName, $isEnabled);
		}
	}

	protected function bootModuleManager()
	{
		$moduleManager = $this->app[ModuleManagerContract::class];
		$event = new InstallModuleEvent($moduleManager);
		event('module.install', $event);

		$moduleManager->installModule();
	}

    public function registerViewFinder()
    {
		$this->app->bind('view.finder',function($app){
			$themeManager = $app['theme'];
			$basePath = config('admin.theme.basePath');
			$themeManager->setBasePath($basePath);
			$themes = config('admin.theme.themes');
			foreach($themes as $group => $theme) {
				$themeManager->setThemes($group, $theme);
			}
			$currentGroup = config('admin.theme.current_group');
			if(is_null($themeManager->getCurrentGroup()))
				$themeManager->setCurrentGroup($currentGroup);
			if(is_null($themeManager->getCurrentTheme($themeManager->getCurrentGroup()))) {
				$currentTheme = config('admin.theme.current_theme');
				$themeManager->set($currentTheme[$themeManager->getCurrentGroup()],
						$themeManager->getCurrentGroup());
			}
			$paths = $themeManager->getAllAvailablePaths();
			return new FileViewFinder($app['files'], $paths);
		});
    }

	protected function registerServiceProviders()
	{
		foreach($this->providers as $provider) {
			$this->app->register($provider);
		}

		AliasLoader::getInstance($this->facades);
	}

	protected function createTopMenu($dispatcher)
	{
		$dispatcher->listen('composing: *',function(){
			\Menu::make('topMenu',function($menu){
				$event = new CreateMenuEvent($menu);
				event('menu.top',$event);
			})->sortBy('order');
		});
	}

	protected function createLeftMenu($dispatcher)
	{
		$dispatcher->listen('composing: *',function(){
			\Menu::make('leftMenu',function($menu){
				$event = new CreateMenuEvent($menu);
				event('menu.left',$event);
			})->sortBy('order');
		});
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

	protected function defaultTopMenu($dispatcher)
	{
		$dispatcher->listen('menu.top',function(CreateMenuEvent $event) {
			$email = 'User';
			if(!empty(\Auth::user()))
				$email = \Auth::user()->email;

			$profile = $event->add($email)->icon('fa fa-fw fa-user')->data('order',50);
			$profile->link->attr(['href'=>'javascript:;','data-toggle'=>'dropdown']);
			$profile->add('Log Out',config('admin.rbac.routeUrlPrefix').'/logout')->icon('fa fa-fw fa-power-off')->data('order',30);
		});

	}

	protected function defaultLeftMenu($dispatcher)
	{
		$dispatcher->listen('menu.left',function(CreateMenuEvent $event){
			$event->add('Dashboard',config('admin.rbac.routeUrlPrefix').'/dashboard')->icon('fa fa-fw fa-dashboard')->data('order',10);
			$settings = $event->add('Settings')->icon('fa fa-gear')->data('order',20)->data('target','settings');
			$settings->link->attr(['href'=>'javascript:;','data-target'=>'#settings','data-toggle'=>'collapse','class'=>'collapsed']);
			$settings->add('Roles','admin/roles')->icon('fa fa-group')->data('order',1);
			$addRole = $settings->add('Add Role',config('admin.rbac.routeUrlPrefix').'/roles/create')->icon('fa fa-plus')->data('order',2);
//			$addRole->divide();
			$settings->add('Permissions',config('admin.rbac.routeUrlPrefix').'/permissions')->icon('fa fa-exclamation-circle')->data('order',3);
		});
	}

	public function provides()
	{
		return [
            'command.laravel-rbac.create-migrations',
            'command.laravel-rbac.update-permission-list',
            'Aliukevicius\LaravelRbac\ActiveUser',
		];
	}
}
