<?php namespace Iyoworks\DependencySorter;

interface SortableInterface {
	
	/**
	 * add an array of items for sorting
	 * @return void
	 */
	public function add(array $item, $allowNumericKey = false);

	/**
	 * add a single item for sorting
	 * @return void
	 */
	public function addItem($item, $dependsOn = null);

	/**
	 * sort the items
	 * @return array|mixed
	 */
	public function sort();

	/**
	 * get circular item list
	 * @return array|mixed
	 */
	public function getCircular();

	/**
	 * get missing item list
	 * @return array|mixed
	 */
	public function getMissing();

	/**
	 * get hit count list
	 * @return array|mixed
	 */
	public function getHits();

}