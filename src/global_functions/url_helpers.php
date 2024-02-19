<?php


if (!function_exists('entity_resource_path'))
{
	/**
	 * Guess the primary resource path from a given URL.
	 * turns /something/12/edit -> /something/12
	 * turns /something/create -> /something
	 * turns /something/new -> /something
	 *
	 * @param $url
	 *
	 * @return string
	 */
	function entity_resource_path($url = '')
	{
		// if the URL is not given, get the current URL
		if ($url === '') $url = request()->url();

		$elements = explode('/', $url);
		$lastElement = end($elements);
		if (in_array($lastElement, ['edit', 'create', 'new'])) {
			array_pop($elements);
			return implode('/', $elements);
		}
		return $url;
	}
}