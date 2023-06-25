<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Utility\Iterator;
use PHPUnit\Framework\TestCase;

final class IteratorTest extends TestCase
{

    private int $i = 0;
    private int $j = 0;

    public function testAdd(): void
    {
        $this->expectNotToPerformAssertions();

        Iterator::add('test1', function() { });
    }

    public function testAll(): void
    {
        $test1 = function() { };
        $test2 = function() { };

        Iterator::add('test1', $test1);
        Iterator::add('test2', $test2);

        $tests = Iterator::all();

        $this->assertIsArray($tests);
        $this->assertCount(2, $tests);
        $this->assertArrayHasKey('test1', $tests);
        $this->assertArrayHasKey('test2', $tests);
        $this->assertSame($test1, $tests['test1']);
        $this->assertSame($test2, $tests['test2']);
    }

    public function testCount(): void
    {
        Iterator::add('test1', function() { });
        Iterator::add('test2', function() { });

        $this->assertSame(2, Iterator::count());
    }

    public function testHasTrue(): void
    {
        Iterator::add('test1', function() { });

        $this->assertTrue(Iterator::has('test1'));
    }

    public function testHasFalse(): void
    {
        Iterator::add('test1', function() { });

        $this->assertFalse(Iterator::has('test2'));
    }

    public function testGet(): void
    {
        $test = function() { };

        Iterator::add('test', $test);

        $this->assertSame($test, Iterator::get('test'));
    }

    public function testGetInvalid(): void
    {
        $this->assertNull(Iterator::get('test'));
    }

    public function testRemove(): void
    {
        Iterator::add('test1', function() { });
        Iterator::add('test2', function() { });

        $this->assertTrue(Iterator::remove('test1'));
        $this->assertFalse(Iterator::has('test1'));
        $this->assertSame(1, Iterator::count());
    }

    public function testRemoveInvalid(): void
    {
        Iterator::add('test1', function() { });

        $this->assertFalse(Iterator::remove('test2'));
        $this->assertSame(1, Iterator::count());
    }

    public function testRun(): void
    {
        Iterator::add('test', function() {
            $this->i++;
        });

        $results = Iterator::run();

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertArrayHasKey('test', $results);
        $this->assertArrayHasKey('time', $results['test']);
        $this->assertArrayHasKey('memory', $results['test']);
        $this->assertArrayHasKey('n', $results['test']);
        $this->assertIsFloat($results['test']['time']);
        $this->assertIsFloat($results['test']['memory']);
        $this->assertSame(1000, $results['test']['n']);
        $this->assertSame(1000, $this->i);
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

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertArrayHasKey('test1', $results);
        $this->assertArrayHasKey('test2', $results);
        $this->assertArrayHasKey('time', $results['test1']);
        $this->assertArrayHasKey('memory', $results['test1']);
        $this->assertArrayHasKey('n', $results['test1']);
        $this->assertArrayHasKey('time', $results['test2']);
        $this->assertArrayHasKey('memory', $results['test2']);
        $this->assertArrayHasKey('n', $results['test2']);
        $this->assertIsFloat($results['test1']['time']);
        $this->assertIsFloat($results['test1']['memory']);
        $this->assertSame(1000, $results['test1']['n']);
        $this->assertIsFloat($results['test2']['time']);
        $this->assertIsFloat($results['test2']['memory']);
        $this->assertSame(1000, $results['test2']['n']);
        $this->assertSame(1000, $this->i);
        $this->assertSame(1000, $this->j);
    }

    public function testRunWithIterations(): void
    {
        Iterator::add('test', function() {
            $this->i++;
        });

        Iterator::run(500);

        $this->assertSame(500, $this->i);
    }

    protected function setUp(): void
    {
        Iterator::clear();
        $this->i = 0;
        $this->j = 0;
    }

}
