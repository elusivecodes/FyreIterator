<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Utility\Iterator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class IteratorTest extends TestCase
{
    protected Iterator $iterator;

    public function testAdd(): void
    {
        $this->assertSame(
            $this->iterator,
            $this->iterator->add('test1', function(): void {})
        );
    }

    public function testAll(): void
    {
        $test1 = function(): void {};
        $test2 = function(): void {};

        $this->iterator->add('test1', $test1);
        $this->iterator->add('test2', $test2);

        $tests = $this->iterator->all();

        $this->assertIsArray($tests);
        $this->assertCount(2, $tests);
        $this->assertArrayHasKey('test1', $tests);
        $this->assertArrayHasKey('test2', $tests);
        $this->assertSame($test1, $tests['test1']);
        $this->assertSame($test2, $tests['test2']);
    }

    public function testCount(): void
    {
        $this->iterator->add('test1', function(): void {});
        $this->iterator->add('test2', function(): void {});

        $this->assertSame(2, $this->iterator->count());
    }

    public function testGet(): void
    {
        $test = function(): void {};

        $this->iterator->add('test', $test);

        $this->assertSame($test, $this->iterator->get('test'));
    }

    public function testGetInvalid(): void
    {
        $this->assertNull($this->iterator->get('test'));
    }

    public function testHasFalse(): void
    {
        $this->iterator->add('test', function(): void {});

        $this->assertFalse($this->iterator->has('invalid'));
    }

    public function testHasTrue(): void
    {
        $this->iterator->add('test', function(): void {});

        $this->assertTrue($this->iterator->has('test'));
    }

    public function testRemove(): void
    {
        $this->iterator->add('test1', function(): void {});
        $this->iterator->add('test2', function(): void {});

        $this->assertSame(
            $this->iterator,
            $this->iterator->remove('test1')
        );

        $this->assertFalse($this->iterator->has('test1'));
        $this->assertTrue($this->iterator->has('test2'));
    }

    public function testRemoveInvalid(): void
    {
        $this->expectException(RuntimeException::class);

        $this->iterator->remove('invalid');
    }

    public function testRun(): void
    {
        $i = 0;
        $this->iterator->add('test', function() use (&$i): void {
            $i++;
        });

        $results = $this->iterator->run();

        $this->assertSame(1000, $i);

        $this->assertArrayHasKey('test', $results);
        $this->assertArrayHasKey('time', $results['test']);
        $this->assertArrayHasKey('memory', $results['test']);
        $this->assertArrayHasKey('n', $results['test']);
        $this->assertIsFloat($results['test']['time']);
        $this->assertIsFloat($results['test']['memory']);
        $this->assertSame(1000, $results['test']['n']);
    }

    public function testRunMultipleTests(): void
    {
        $i = 0;
        $j = 0;
        $this->iterator->add('test1', function() use (&$i): void {
            $i++;
        });
        $this->iterator->add('test2', function() use (&$j): void {
            $j++;
        });

        $results = $this->iterator->run();

        $this->assertSame(1000, $i);
        $this->assertSame(1000, $j);

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
    }

    public function testRunWithIterations(): void
    {
        $i = 0;
        $this->iterator->add('test', function() use (&$i): void {
            $i++;
        });

        $this->iterator->run(500);

        $this->assertSame(500, $i);
    }

    protected function setUp(): void
    {
        $this->iterator = new Iterator();
    }
}
