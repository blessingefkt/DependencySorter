<?php namespace Iyoworks\DependencySorter;

interface DependableInterface {
	/**
	 * get dependencies
	 * @return array
	 */
	public function getDependencies();

	public function getHandle();
}