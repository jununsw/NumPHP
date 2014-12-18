<?php
/**
 * NumPHP (http://numphp.org/)
 *
 * @link http://github.com/GordonLesti/NumPHP for the canonical source repository
 * @copyright Copyright (c) 2014 Gordon Lesti (http://gordonlesti.com/)
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace NumPHPTest\LinAlg\LinAlgTest;

use NumPHP\Core\NumArray;
use NumPHP\Core\NumPHP;
use NumPHP\LinAlg\LinAlg;

/**
 * Class LinAlgTest
  * @package NumPHPTest\LinAlg\LinAlgTest
  */
class LinAlgTest extends \PHPUnit_Framework_TestCase
{
    public function testDet3x3()
    {
        $numArray = new NumArray(
            [
                [1, 6, 1],
                [2, 3, 2],
                [4, 2, 1],
            ]
        );

        $this->assertSame(27.0, LinAlg::det($numArray));
    }

    /**
     * @expectedException \NumPHP\LinAlg\Exception\InvalidArgumentException
     * @expectedExceptionMessage NumArray with shape (2, 3) given, NumArray has to be square
     */
    public function testDet2x3()
    {
        $numArray = NumPHP::arange(1, 6)->reshape(2, 3);

        LinAlg::det($numArray);
    }

    /**
     * @expectedException \NumPHP\LinAlg\Exception\InvalidArgumentException
     * @expectedExceptionMessage NumArray with dimension 3 given, NumArray should have 2 dimensions
     */
    public function testDet2x2x2()
    {
        $numArray = NumPHP::arange(1, 8)->reshape(2, 2, 2);

        LinAlg::det($numArray);
    }
}