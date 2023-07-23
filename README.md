# FyreIterator

**FyreIterator** is a free, open-source iteration library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/iterator
```

In PHP:

```php
use Fyre\Utility\Iterator;
```


## Methods

**Add**

Add a test

- `$name` is a string representing the test name.
- `$callback` is the callback to execute.

```php
Iterator::add($name, $callback);
```

**All**

Get all tests.

```php
$tests = Iterator::all();
```

**Clear**

Clear all tests.

```php
Iterator::clear();
```

**Count**

Get the number of tests.

```php
$testCount = Iterator::count();
```

**Get**

Get a specific test callback.

- `$name` is a string representing the test name.

```php
$test = Iterator::get($name);
```

**Has**

Determine whether a test exists.

- `$name` is a string representing the test name.

```php
$hasTest = Iterator::has($name);
```

**Remove**

Remove a test.

- `$name` is a string representing the test name.

```php
$removed = Iterator::remove($name);
```

**Run**

Run the tests and return the results.

- `$iterations` is a number representing the number of iterations to run, and will default to *1000*.

```php
$results = Iterator::run($iterations);
```