<?php namespace Iyoworks\DependencySorter;

interface DependableInterface {
	/**
	 * get dependencies
	 * @return array
	 */
	public function getDependencies();

	/**
	 * get item key/identifier
	 * @return string|mixed
	 */
	public function getHandle();
}