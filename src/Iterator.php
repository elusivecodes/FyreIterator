<?php
declare(strict_types=1);

namespace Fyre\Utility;

use function
    call_user_func,
    gc_collect_cycles,
    max,
    memory_get_usage,
    microtime,
    strtolower;

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
        $name = static::formatKey($name);

		static::$tests[$name] = $callback;
    }

    /**
     * Clear the tests.
     */
    public static function clear(): void
    {
        static::$tests = [];
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

            $start = static::now();
            $startMemory = $maxMemory = static::memory();

            for ($i = 0; $i < $iterations; $i++) {
                $result = call_user_func($test);
                $currentMemory = static::memory();
                $maxMemory = max($maxMemory, $currentMemory);
                unset($result);
            }

            $end = static::now();

            $results[$name] = [
                'time' => $end - $start,
                'memory' => $maxMemory - $startMemory,
                'n' => $iterations
            ];
        }

        return $results;
    }

    /**
     * Format a test key.
     * @param string $name The test name.
     * @return string The test key.
     */
    protected static function formatKey(string $name): string
    {
        return strtolower($name);
    }

    /**
     * Get the current memory usage in bytes.
     * @return float The current memory usage in bytes.
     */
    protected static function memory(): int
    {
        return memory_get_usage(true);
    }

    /**
     * Get the current UTC timestamp with microseconds.
     * @return float The current UTC timestamp with microseconds.
     */
    protected static function now(): float
    {
        return microtime(true);
    }

}
