<?php namespace Mirage\Admin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use DB;
/**
 * Description of AdminCommand
 *
 * @author Bryan Salazar
 */
class AdminInstallCommand extends Command
{
	protected $signature = 'admin:install';
	protected $description = 'Create default tables for admin add create default user';
	protected $email;
	protected $password;
	protected $confirmPassword;
	protected $role;
	protected $roleDescription;

	public function handle()
	{
		$this->email = $this->ask('Please enter email: ');
		$this->password  = $this->ask('Please enter password: ');
		$this->confirmPassword = $this->ask('Confirm password: ');
		$this->role = $this->ask('What is you role?');
		$this->roleDescription = $this->ask('What is role description?');

		if($this->password === $this->confirmPassword) {
			$this->call('laravel-rbac:create-migrations');
			$this->call('migrate');

			// create users, roles adn user_role
			$userID = DB::table('users')->insertGetId(['email' => $this->email,'password' => bcrypt($this->password)]);
			$roleID = DB::table('roles')->insertGetId(['name'=> $this->role, 'description' => $this->roleDescription]);
			DB::table('user_role')->insert(['user_id'=>$userID,'role_id'=>$roleID]);

			$this->call('vendor:publish');

			// override the laravel-menu views
			app('files')->copy(__DIR__ . '/stubs/views.php', config_path('laravel-menu/views.php'));
		} else {
			$this->error('Confirm password did not match. Please try again.');
		}
	}
}