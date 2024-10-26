# FyreIterator

**FyreIterator** is a free, open-source iteration library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Iterator Creation](#iterator-creation)
- [Iterator Methods](#iterator-methods)



## Installation

**Using Composer**

```
composer require fyre/iterator
```

In PHP:

```php
use Fyre\Utility\Iterator;
```


## Iterator Creation

```php
$iterator = new Iterator();
```


## Iterator Methods

**Add**

Add a test

- `$name` is a string representing the test name.
- `$callback` is the callback to execute.

```php
$iterator->add($name, $callback);
```

**All**

Get all tests.

```php
$tests = $iterator->all();
```

**Clear**

Clear all tests.

```php
$iterator->clear();
```

**Count**

Get the number of tests.

```php
$count = $iterator->count();
```

**Get**

Get a specific test callback.

- `$name` is a string representing the test name.

```php
$test = $iterator->get($name);
```

**Has**

Determine whether a test exists.

- `$name` is a string representing the test name.

```php
$hasTest = $iterator->has($name);
```

**Remove**

Remove a test.

- `$name` is a string representing the test name.

```php
$iterator->remove($name);
```

**Run**

Run the tests and return the results.

- `$iterations` is a number representing the number of iterations to run, and will default to *1000*.

```php
$results = $iterator->run($iterations);
```