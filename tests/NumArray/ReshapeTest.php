<?php
declare(strict_types=1);

namespace NumPHPTest\NumArray;

use NumPHP\Exception\IllegalArgumentException;
use NumPHP\NumArray;
use PHPUnit\Framework\TestCase;

class ReshapeTest extends TestCase
{
    public function test2x3Reshape5()
    {
        $this->expectException(IllegalArgumentException::class);
        $this->expectExceptionMessage('Size of new shape 5 is different to size 6');
        NumArray::ones(2, 3)->reshape(5);
    }

    public function test2x3Reshape6()
    {
        $numArray = new NumArray([
            [7, 8, 7],
            [4, 1, -7]
        ]);
        $this->assertTrue($numArray->reshape(6)->isEqual(new NumArray([7, 8, 7, 4, 1, -7])));
    }

    public function test6Reshape2x3()
    {
        $numArray = new NumArray([7, 9, -4, 0, 0, -2]);
        $this->assertTrue($numArray->reshape(2, 3)->isEqual(new NumArray([
            [7, 9, -4],
            [0, 0, -2]
        ])));
    }

    public function test2x3Reshape3x2()
    {
        $numArray = new NumArray([
            [-1, 9, -5],
            [-1, -9, 1]
        ]);
        $this->assertTrue($numArray->reshape(3, 2)->isEqual(new Numarray([
            [-1, 9],
            [-5, -1],
            [-9, 1]
        ])));
    }
}
