<?php namespace Mirage\Admin\Ext\Menu;

use Lavary\Menu\Item as LavaryItem;

/**
 * Description of Item
 *
 * @author Bryan Salazar
 */
class Item extends LavaryItem
{
	public function icon($icon)
	{
		$this->prepend('<i class="'.$icon.'"></i> ');
		return $this;
	}

	public function iconAppend($icon)
	{
		$this->append(' <i class="'.$icon.'"></i>');
		return $this;
	}
}
