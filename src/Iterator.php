<?php
declare(strict_types=1);

namespace Fyre\Utility;

use RuntimeException;

use function array_key_exists;
use function count;
use function gc_collect_cycles;
use function hrtime;
use function max;
use function memory_get_usage;

/**
 * Iterator
 */
class Iterator
{
    protected array $tests = [];

    /**
     * Add a test.
     *
     * @param string $name The test name.
     * @param callable $callback The callback.
     * @return static The Iterator.
     */
    public function add(string $name, callable $callback): static
    {
        $this->tests[$name] = $callback;

        return $this;
    }

    /**
     * Get all tests.
     *
     * @return array The tests.
     */
    public function all(): array
    {
        return $this->tests;
    }

    /**
     * Clear the tests.
     */
    public function clear(): void
    {
        $this->tests = [];
    }

    /**
     * Get the number of tests.
     *
     * @return int The number of tests.
     */
    public function count(): int
    {
        return count($this->tests);
    }

    /**
     * Get a specific test callback.
     *
     * @param string $name The test name.
     * @return callable|null The test callback.
     */
    public function get(string $name): callable|null
    {
        return $this->tests[$name] ?? null;
    }

    /**
     * Determine whether a test exists.
     *
     * @param string $name The test name.
     * @return bool TRUE if the test exists, otherwise FALSE.
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->tests);
    }

    /**
     * Remove a test.
     *
     * @param string $name The test name.
     * @return static The Iterator.
     */
    public function remove(string $name): static
    {
        if (!array_key_exists($name, $this->tests)) {
            throw new RuntimeException('Invalid iterator: '.$name);
        }

        unset($this->tests[$name]);

        return $this;
    }

    /**
     * Run the tests and return the results.
     *
     * @param int $iterations The number of iterations to run.
     * @return array The test results.
     */
    public function run(int $iterations = 1000): array
    {
        $results = [];

        foreach ($this->tests as $name => $test) {
            gc_collect_cycles();

            $start = hrtime(true);
            $startMemory = memory_get_usage(true);
            $maxMemory = 0;

            for ($i = 0; $i < $iterations; $i++) {
                $result = $test();
                $maxMemory = max($maxMemory, memory_get_usage(true));
                unset($result);
            }

            $end = hrtime(true);

            $results[$name] = [
                'time' => ($end - $start) / 1000,
                'memory' => max(.0, $maxMemory - $startMemory),
                'n' => $iterations,
            ];
        }

        return $results;
    }
}
