<?php 
class TestCase extends PHPUnit_Framework_TestCase {

	public function assertArrayKeyNotExist($key, array $arr)
	{
		$this->assertFalse(array_key_exists($key, $arr), "Array key ($key) exists");
	}

	public function assetArraysSimilar($a, $b) {
		if (count(array_diff_key($a, $b))) {
			return false;
		}
		$pass = true;
		foreach($a as $k => $v) {
			if ($v !== $b[$k]) {
				return $pass = false;
			}
		}
		$this->assertTrue($pass);
	}

	public function tearDown()
	{
		// Mockery::close();
	}
}