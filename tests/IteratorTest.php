<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Utility\Iterator,
    PHPUnit\Framework\TestCase;

final class IteratorTest extends TestCase
{

    private int $i = 0;
    private int $j = 0;

    public function testRun(): void
    {
        Iterator::add('test', function() {
            $this->i++;
        });

        $results = Iterator::run();

        $this->assertCount(1, $results);
        $this->assertArrayHasKey('test', $results);
        $this->assertArrayHasKey('time', $results['test']);
        $this->assertArrayHasKey('memory', $results['test']);
        $this->assertArrayHasKey('n', $results['test']);

        $this->assertEquals(1000, $this->i);
    }

    public function testRunMultipleTests(): void
    {
        Iterator::add('test1', function() {
            $this->i++;
        });
        Iterator::add('test2', function() {
            $this->j++;
        });

        $results = Iterator::run();

        $this->assertCount(2, $results);
        $this->assertArrayHasKey('test1', $results);
        $this->assertArrayHasKey('test2', $results);

        $this->assertEquals(1000, $this->i);
        $this->assertEquals(1000, $this->j);
    }

    public function testRunWithIterations(): void
    {
        Iterator::add('test', function() {
            $this->i++;
        });

        Iterator::run(500);

        $this->assertEquals(500, $this->i);
    }

    protected function setUp(): void
    {
        Iterator::clear();
        $this->i = 0;
        $this->j = 0;
    }

}
