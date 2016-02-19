<?php namespace Mirage\Admin\Events;

//use Mirage\Admin\Ext\Menu\Builder;

/**
 * Description of CreateMenuEvent
 *
 * @author Bryan Salazar
 */
class CreateMenuEvent
{
	/**
	 *
	 * @var Builder
	 */
	protected $menu;

	public function __construct($menu)
	{
		$this->menu = $menu;
	}

	/**
	 *
	 * @return Builder
	 */
	public function getMenu()
	{
		return $this->menu;
	}

	public function add($title, $options = '')
	{
		return $this->menu->add($title, $options);
	}
}
