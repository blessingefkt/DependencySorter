<?php
use Iyoworks\DependencySorter\Sorter;
use Iyoworks\DependencySorter\DependableInterface;

debugOn();
class SorterTest extends TestCase {
	
	/**
	 * @var \Iyoworks\DependencySorter\SortableInterface
	 */
	protected $s;
	
	public function testSortGoodSet() 
	{
		$this->load(array(
			'hello' => [],
			'helloGalaxy' => ['helloWorld'],
			'father' => [],
			'mother' => [],
			'child' => ['father', 'mother'],
			'helloWorld' => ['hello'],
			'family' => ['father', 'mother', 'child']
			));
		$this->expected = array(
			'hello',
			'father',
			'mother',
			'child',
			'helloWorld',
			'family',
			'helloGalaxy',
			);
		$this->result = $this->s->sort();

		$this->analyze();
		
		$this->assertSame($this->expected, array_values($this->result), $this->msg());
		
		$this->assertEmpty($this->s->getCircular());

		$this->assertEmpty($this->s->getMissing());
	}

	public function testSortMissingSet() 
	{
		$this->load(array(
			'helloWorld' => [],
			'helloGalaxy' => ['helloWorld'],
			'father' => [],
			'child' => ['father', 'mother'],
			'family' => ['father', 'mother', 'child']
			));
		$this->expected = array(
			'helloWorld',
			'helloGalaxy',
			'father'
			);
		$this->result = $this->s->sort();

		$this->analyze();

		$this->assertSame($this->expected, array_values($this->result), $this->msg());

		$this->assertEmpty($this->s->getCircular());

		$this->assertTrue($this->s->isMissing('mother'));
		
		$this->assertTrue($this->s->hasMissing('child'));
		
		$this->assertTrue($this->s->hasMissing('family'));
	}

	public function testSortCircularSet() 
	{
		$this->load( array(
			'helloWorld' => ['hello'],
			'helloGalaxy' => ['helloWorld'],
			'mother' => ['father'],
			'child' => ['father', 'mother'],
			'father' => ['mother', 'baby'],
			'baby' => ['father']
			));
		$this->expected = array( );
		$this->result = $this->s->sort();

		$this->analyze();

		$this->assertSame($this->expected, array_values($this->result), $this->msg());

		$this->assertTrue($this->s->isCircular('mother'));

		$this->assertTrue($this->s->isCircular('baby'));

		$this->assertTrue($this->s->isCircular('father'));
		
		$this->assertTrue($this->s->hasCircular('father'));
		
		$this->assertTrue($this->s->hasCircular('baby'));

		$this->assertTrue($this->s->hasCircular('mother'));

		$this->assertTrue($this->s->isMissing('hello'));

		$this->assertTrue($this->s->hasMissing('helloWorld'));
	}

	public function tearDown()
	{
		unset($this->expected);
		unset($this->result);
		unset($this->s);
	}

	protected function load($arr){
		$this->s = new Sorter;
		foreach ($arr as $key => $deps) {
			$this->s->add($key, $deps);
		}
	}

	protected function msg(){
		return sprintf("Expected: %s\nResult: %s\n", 
			join(", ", $this->expected), 
			join(", ", $this->result)
			);
	}

	protected function analyze(){

		_d("\nLIST: [%s]", $this->result);
		{
			_d("\t [#] %12s %4s %4s %12s %4s %12s", 'item', 'dep\'es', 'missing', '', 'circular', '');
			foreach ($this->s->getHits() as $n => $c) {
				_d("\t [%d] %12s %4s %4s %12s %4s %12s", 
					$c, 
					$n, 
					$this->s->hasDependents($n) ? count($this->s->getDependents($n)) : 0,
					$this->s->hasMissing($n) ? count($this->s->getMissing($n)) : 0,
					$this->s->hasMissing($n) ? $this->s->getMissing($n) : '',
					$this->s->isCircular($n) ? count($this->s->getCircular($n)) : 0,
					$this->s->isCircular($n) ? $this->s->getCircular($n) : '');
			}
		}

		_d("MISSING");
		foreach ($this->s->getMissing() as $key => $value) {
			_d("\t%s => %s", $key, $value);
		}
	}
}