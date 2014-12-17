<?php
/**
 * NumPHP (http://numphp.org/)
 *
 * @link http://github.com/GordonLesti/NumPHP for the canonical source repository
 * @copyright Copyright (c) 2014 Gordon Lesti (http://gordonlesti.com/)
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace NumPHPTest\LinAlg;

use NumPHP\Core\NumArray;
use NumPHP\Core\NumPHP;
use NumPHP\LinAlg\LUDecomposition;

/**
 * Class LUDecompositionTest
  * @package NumPHPTest\LinAlg
  */
class LUDecompositionTest extends \PHPUnit_Framework_TestCase
{
    public function testLUDecompositionSquare()
    {
        $numArray = new NumArray(
            [
                [1, 6, 1],
                [2, 3, 2],
                [4, 2, 1],
            ]
        );

        $expectedP = new NumArray(
            [
                [0, 1, 0],
                [0, 0, 1],
                [1, 0, 0],
            ]
        );
        $expectedL = new NumArray(
            [
                [  1,    0, 0],
                [1/2,    1, 0],
                [1/4, 4/11, 1],
            ]
        );
        $expectedU = new NumArray(
            [
                [4,    2,     1],
                [0, 11/2,   3/4],
                [0,    0, 27/22],
            ]
        );
        $expectedResult = [
            'P' => $expectedP,
            'L' => $expectedL,
            'U' => $expectedU,
        ];
        $this->assertEquals($expectedResult, LUDecomposition::lud($numArray));
    }

    public function testLUDecomposition2x4()
    {
        $numArray = new NumArray(
            [
                [4, 2, 1, 8],
                [2, 3, 5, 1],
            ]
        );

        $expectedP = NumPHP::identity(2);
        $expectedL = new NumArray(
            [
                [  1, 0],
                [1/2, 1],
            ]
        );
        $expectedU = new NumArray(
            [
                [4, 2,   1,  8],
                [0, 2, 9/2, -3],
            ]
        );
        $expectedResult = [
            'P' => $expectedP,
            'L' => $expectedL,
            'U' => $expectedU,
        ];
        $this->assertEquals($expectedResult, LUDecomposition::lud($numArray));
    }

    /**
     * @expectedException \NumPHP\LinAlg\Exception\InvalidArgumentException
     * @expectedExceptionMessage NumArray with dimension 1 given, NumArray should have 2 dimensions
     */
    public function testLUDecompositionVector()
    {
        $numArray = NumPHP::arange(1, 2);

        LUDecomposition::lud($numArray);
    }
}
