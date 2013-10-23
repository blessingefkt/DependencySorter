<?php namespace Iyoworks\DependencySorter;

class Sorter implements SortableInterface {
	private $items = [];
	private $dependencies = [];
	private $dependsOn = [];
	private $missing = [];
	private $circular = [];
	private $hits = [];
	private $sorted = [];

	public function add($item, $_deps = null)
	{
		list($item, $_deps) = $this->prepNewItem($item, $_deps);
		$this->setItem($item, $_deps);
	}

	public function addArray(array $item, $allowNumericKey = false)
	{
		foreach ($item as $key => $value) {
			if(!$allowNumericKey and is_int($key)) 
				$this->add($value);
			else
				$this->add($key, $value);		
		}
	}

	public function sort()
	{
		$this->sorted = array();
		$hasChanged = true;

		while (count($this->sorted) < count($this->items) && $hasChanged)
		{
			$hasChanged = false;

			foreach ($this->dependencies as $item => $deps)
			{
				if ($this->satisfied($item))
				{
					$this->setSorted($item);
					$this->removeDependents($item);
					$hasChanged = true;
				}

				$this->hits[$item]++;
			}
		}

		return $this->sorted;
	}

	protected function setItem($item, array $_deps){
		$this->items[] = $item;
		foreach ($_deps as $_dep)
		{
			$this->items[] = $_dep;
			$this->dependsOn[$_dep][$item] = $item;
			$this->hits[$_dep] = 0;
		}

		$this->items = array_unique($this->items);
		$this->dependencies[$item] = $_deps;
		$this->hits[$item] = 0;
	}

	protected function prepNewItem($item, $_deps){
		if($item instanceof DependableInterface)
		{
			$_deps = $item->getDependencies();
			$item = $item->getHandle();
		}
		elseif($_deps instanceof DependableInterface)
		{
			$_deps = $_deps->getDependencies();
		}

		if(empty($_deps))
		{
			$_deps = [];
		}
		elseif(is_string($_deps))
		{
			$_deps = (array) preg_split('/,\s?/', $_deps);
		}

		return [$item, $_deps];
	}

	protected function satisfied($item)
	{
		$pass = true;
		foreach ($this->getDependents($item) as $dep)
		{
			if (!$this->isSorted($dep))
			{
				if(!$this->hasDependents($dep))
					$this->setMissing($item, $dep);
				$pass = false;
			}

			if ($this->isDependent($item, $dep))
			{
				$this->setCircular($item, $dep);
				$pass = false;
			}
		}
		return $pass;
	}

	protected function setSorted($item)
	{
		$this->sorted[] = $item;
	}

	protected function removeDependents($item)
	{
		unset($this->dependencies[$item]);
		foreach ($this->dependsOn as $_d => &$_items) {
			// unset($_items[$item]);
		}

		_d("removed\n\t %s", array_keys($this->dependencies));
	}

	protected function setCircular($item, $item2)
	{
		$this->circular[$item][$item2] = $item2;
	}

	protected function setMissing($item, $item2)
	{
		$this->missing[$item][$item2] = $item2;
	}

	protected function isSorted($item)
	{
		return in_array($item, $this->sorted);
	}

	public function isDependent($item, $item2)
	{
		return isset($this->dependsOn[$item][$item2]);
	}

	public function hasDependents($item)
	{
		return isset($this->dependencies[$item]);
	}

	public function hasMissing($item)
	{
		return isset($this->missing[$item]);
	}

	public function isMissing($dep)
	{
		foreach ($this->missing as $item => $deps) {
			if(in_array($dep, $deps))
				return true;
		}
	}

	public function hasCircular($item)
	{
		return isset($this->circular[$item]);
	}

	public function isCircular($dep)
	{
		foreach ($this->circular as $item => $deps) {
			if(in_array($dep, $deps))
				return true;
		}
	}

	/**
	 * get depenencies of an item
	 * @return array
	 */
	public function getDependents($item)
	{
		return $this->dependencies[$item];
	}

	/**
	 * get missing msgs
	 * @return array
	 */
	public function getMissing($str = null)
	{
		if($str) return $this->missing[$str];
		return $this->missing;
	}

	/**
	 * get circular msgs
	 * @return array
	 */
	public function getCircular($str = null)
	{
		if($str) return $this->circular[$str];
		return $this->circular;
	}

	/**
	 * get hit counts
	 * @return array|int
	 */
	public function getHits($str = null)
	{
		if($str) return $this->hits[$str];
		return $this->hits;
	}

}