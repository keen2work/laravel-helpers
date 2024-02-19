<?php


namespace EMedia\Helpers\Components\Menu;


use Illuminate\Support\Collection;

class MenuBar
{

	protected static $menubarItems = [];


	/**
	 *
	 * Add a new MenuItem
	 *
	 * @param MenuItem $menuItem
	 * @param string   $parent
	 */
	public static function add(MenuItem $menuItem, $parent = 'default')
	{
		if (empty(self::$menubarItems[$parent])) {
			self::$menubarItems[$parent] = new Collection();
		}

		self::$menubarItems[$parent]->push($menuItem);
	}

	/**
	 *
	 * Get a list of menu items
	 *
	 * @param string $parent
	 *
	 * @return mixed
	 */
	public static function menuItems($parent = 'default')
	{
		if (empty(self::$menubarItems[$parent])) return [];

		// sort by order first
		$itemsWithOrder = self::$menubarItems[$parent]->filter(function ($item) {
			return $item->getOrder() > 0;
		})->sort();

		// sort by text then
		$itemsWithoutOrder = self::$menubarItems[$parent]->filter(function ($item) {
			return empty($item->getOrder());
		})->sort();

		return $itemsWithOrder->merge($itemsWithoutOrder);
	}

}