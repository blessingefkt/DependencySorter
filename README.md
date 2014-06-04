DependencySorter
================
Sorts items according to a list of dependencies.

This package provides 2 interfaces:
<dl>
  <dt>Iyoworks\DependencySorter\SortableInterface</dt>
  <dd>implemented by objects that conducting the sorting</dd>
  <dt>Iyoworks\DependencySorter\DependableInterface</dt>
  <dd>implemented by objects that are to be sorted</dd>
</dl>

Items can be passed to the sorter in a group via `Sorter::add(array)`, or individuallay via `Sorter::addItem(item, dependencies)`.

The provided `Sorter` implementaion can get dependencies from:

- a comma delimted string
- an array
- an object that implements `DependableInterface`

```
$sorter = new Iyoworks\DependencySorter\Sorter;
$sorter->add( array(
	'couple' => 'father, mother'
	'mother' => 'father',
	'father' => null,
	));
$orderedItems = $sorter->sort();
```
The sorted list of items would be:
```
Array
(
    [0] => father
    [1] => mother
    [2] => couple
)
```
Missing Dependencies
---------
Items missing dependencies are identified.

```
$sorter = new Iyoworks\DependencySorter\Sorter;
$sorter->add( array(
	'couple' => 'father, mother'
	'mother' => 'child',
	'father' => 'mother',
	) );
$orderedItems = $sorter->sort(); //returns an empty array
```
The missing dependencies can be retrieved
```
$missing = $sorter->getMissing(); 
```
The missing items would be:
```
Array
(
    [mother] => Array
        (
            [child] => child
        )
)
```
Missing dependencies of an item can be retrieved individually
```
$missing = $sorter->getMissing('mother'); 
```
You can check if an item has missing dependencies
```
$sorter->hasMissing($item);
```
You can also check if an item is a missing dependency
```
$sorter->isMissing($dep);
```

Circular Dependencies
-----------
Circular dependencies are identified.
```
$sorter = new Iyoworks\DependencySorter\Sorter;
$sorter->add( array(
	'couple' => 'father, mother'
	'mother' => 'father',
	'father' => 'mother',
	) );
$orderedItems = $sorter->sort(); //returns an empty array
$circular = $sorter->getCircular(); 
```
The list of circular items would be:
```
Array
(
    [mother] => Array
        (
            [father] => father
        )
    [father] => Array
        (
            [mother] => mother
        )
)
```
The circular dependencies of an item can be retrieved individually.
```
$circular = $sorter->getCircular('father'); 
```
You can check if an item has circular dependencies.
```
$sorter->hasCircular($item);
```
You can also check if an item is a circular dependency.
```
$sorter->isCircular($dep);
```
