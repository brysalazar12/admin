<?php namespace Mirage\Admin\Ext\Menu;
use Lavary\Menu\Menu as LavaryMenu;
use Lavary\Menu\Collection;
use Mirage\Admin\Ext\Menu\Builder;

/**
 * Description of Menu
 *
 * @author Bryan Salazar
 */
class Menu extends LavaryMenu
{
	public function setCollection(Collection $collection)
	{
		$this->collection = $collection;
	}

	public function make($name, $callback)
	{
		if(is_callable($callback))
		{

			$menu = new Builder($name, $this->loadConf($name));

			// Registering the items
			call_user_func($callback, $menu);

			// Storing each menu instance in the collection
			$this->collection->put($name, $menu);

			// Make the instance available in all views
			\View::share($name, $menu);

			return $menu;
		}
	}
}
