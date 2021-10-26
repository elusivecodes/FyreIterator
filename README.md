# FyreIterator

**FyreIterator** is a free, iteration library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer install fyre/iterator
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

**Clear**

Clear all tests.

```php
Iterator::clear();
```

**Run**

Run the tests and return the results.

- `$iterations` is a number representing the number of iterations to run, and will default to *1000*.

```php
$results = Iterator::run($iterations);
```