<?php namespace Mirage\Admin\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Description of AdminModuleCommand
 *
 * @author Bryan Salazar
 */
class AdminModuleCommand extends GeneratorCommand
{
	protected $currentStub;
	protected $moduleName;
	protected $name = 'admin:module';

	public function fire()
	{
		$this->moduleName = studly_case(ucfirst($this->getNameInput()));
//		$this->
//		$module = Module
//		if($this->files->exists($path)) {
//
//		}

		// create module
		// folder, module service provider, route file


	}

	protected function getStub()
	{
		return $this->currentStub;
	}
}
