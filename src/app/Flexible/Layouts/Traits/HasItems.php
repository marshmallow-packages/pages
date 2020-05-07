<?php

namespace Marshmallow\Pages\Flexible\Layouts\Traits;

trait HasItems
{
	public function items ()
	{
		$items = [];
		foreach ($this->{$this->items_attribute} as $item) {
			$items[] = (object) $item['attributes'];
		}
		return $items;
	}

	public function __get ($parameter)
	{
		switch ($parameter) {
			case 'items':
				if (!isset($this->items_attribute) || !$this->items_attribute) {
					throw new \Exception('Please set `protected $items_attribute` in ' . get_class($this), 1);
				}
				return $this->items();
				break;
		}

		return parent::__get($parameter);
	}
}