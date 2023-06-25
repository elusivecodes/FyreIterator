<?php
declare(strict_types=1);

namespace Fyre\Utility;

use function array_key_exists;
use function call_user_func;
use function count;
use function gc_collect_cycles;
use function max;
use function memory_get_usage;
use function microtime;

/**
 * Iterator
 */
abstract class Iterator
{

    protected static array $tests = [];

    /**
     * Add a test.
     * @param string $name The test name.
     * @param callable $callback The callback.
     */
    public static function add(string $name, callable $callback): void
    {
        static::$tests[$name] = $callback;
    }

    /**
     * Get all tests.
     * @return array The tests.
     */
    public static function all(): array
    {
        return static::$tests;
    }

    /**
     * Clear the tests.
     */
    public static function clear(): void
    {
        static::$tests = [];
    }

    /**
     * Get the number of tests.
     * @return int The number of tests.
     */
    public static function count(): int
    {
        return count(static::$tests);
    }

    /**
     * Get a specific test callback.
     * @param string $name The test name.
     * @return callable|null The test callback.
     */
    public static function get(string $name): callable|null
    {
        return static::$tests[$name] ?? null;
    }

    /**
     * Determine whether a test exists.
     * @param string $name The test name.
     * @return bool TRUE if the test exists, otherwise FALSE.
     */
    public static function has(string $name): bool
    {
        return array_key_exists($name, static::$tests);
    }

    /**
     * Remove a test.
     * @param string $name The test name.
     * @return bool TRUE if the test was removed, otherwise FALSE.
     */
    public static function remove(string $name): bool
    {
        if (!array_key_exists($name, static::$tests)) {
            return false;
        }

        unset(static::$tests[$name]);

        return true;
    }

    /**
     * Run the tests and return the results.
     * @param int $iterations The number of iterations to run.
     * @return array The test results.
     */
    public static function run(int $iterations = 1000): array
    {
        $results = [];

        foreach (static::$tests AS $name => $test) {
            gc_collect_cycles();

            $start = microtime(true);
            $startMemory = memory_get_usage(true);
            $maxMemory = 0;

            for ($i = 0; $i < $iterations; $i++) {
                $result = call_user_func($test);
                $maxMemory = max($maxMemory, memory_get_usage(true));
                unset($result);
            }

            $end = microtime(true);

            $results[$name] = [
                'time' => $end - $start,
                'memory' => max(.0, $maxMemory - $startMemory),
                'n' => $iterations
            ];
        }

        return $results;
    }

}
