<?php namespace Mirage\Admin\Ext\Menu;

use Lavary\Menu\Builder as LavaryBuilder;
use Mirage\Admin\Ext\Menu\Item;
/**
 * Description of Builder
 *
 * @author Bryan Salazar
 */
class Builder extends LavaryBuilder
{
	/**
	 *
	 * @var \Lavary\Menu\Collection
	 */
	protected $items;

	public function remove($keys)
	{
		$this->items->forget($keys);
	}

	public function getKeys()
	{
		return $this->items->keys();
	}

	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Adds an item to the menu
	 *
	 * @param  string  $title
	 * @param  string|array  $acion
	 * @return Lavary\Menu\Item $item
	 */
	public function add($title, $options = '')
	{

		$id = isset($options['id']) ? $options['id'] : $this->titleId($title);

		$item = new Item($this, $id, $title, $options);

		$this->items->push($item);

		return $item;
	}

	protected function titleId($title)
	{
		$titles = explode(' ', $title);
		return strtolower(implode('-', $titles));
	}
}
