<?php


namespace EMedia\Helpers\Components\Menu;


use Illuminate\Contracts\Support\Arrayable;

class MenuItem implements Arrayable
{

	protected $text;

	protected $url;

	protected $resource;

	protected $class;

	protected $order;

	protected $permission;

	/**
	 * @param mixed $text
	 */
	public function setText($text)
	{
		$this->text = $text;

		return $this;
	}

	/**
	 * @param mixed $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @param mixed $resource
	 */
	public function setResource($resource)
	{
		$this->resource = $resource;

		return $this;
	}

	/**
	 * @param mixed $class
	 */
	public function setClass($class)
	{
		$this->class = $class;

		return $this;
	}

	/**
	 * @param mixed $order
	 */
	public function setOrder($order)
	{
		$this->order = (int) $order;

		return $this;
	}


	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'text' => $this->text,
			'url' => $this->url,
			'resource' => $this->resource,
			'class' => $this->class,
			'order' => $this->order,
			'permission' => $this->permission,
		];
	}

	/**
	 * @return mixed
	 */
	public function getPermission()
	{
		return $this->permission;
	}

	/**
	 * @param mixed $permission
	 */
	public function setPermission($permission)
	{
		$this->permission = $permission;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @return mixed
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * @return mixed
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return mixed
	 */
	public function getClass()
	{
		return $this->class;
	}
}