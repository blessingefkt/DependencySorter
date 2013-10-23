<?php namespace Iyoworks\DependencySorter;

interface SortableInterface {
	
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

	public function add($item, $dependsOn = null);

	public function getHits();

}