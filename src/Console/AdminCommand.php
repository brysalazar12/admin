<?php namespace Mirage\Admin\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Description of AdminCommand
 *
 * @author Bryan Salazar
 */
class AdminCommand extends GeneratorCommand
{
	protected $currentStub;
	protected $moduleName;
	protected $name = 'admin:install';

	public function fire()
	{
		$this->moduleName = $this->getNameInput();


		// create module
		// folder, module service provider, route file


	}

//	protected function getOptions()
//	{
//		return [
//			['module','mod',  InputOption::VALUE_OPTIONAL, 'Module Name. Create a module basic structure'],
//			[]
//		];
//	}

	protected function getStub()
	{
		return $this->currentStub;
	}
}
