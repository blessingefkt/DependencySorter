DependencySorter
================

Sorts items according to a list of dependencies

Example
========

```
$sorter = new Iyoworks\DependencySorter\Sorter;
$sorter->add( array(
			'couple' => 'father, mother'
			'mother' => 'father',
			'father' => null,
			) );
$items = $sorter->sort();

print_r($items);

```

