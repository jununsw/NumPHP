<?php
declare(strict_types=1);

namespace NumPHP;

use NumPHP\Exception\IllegalArgumentException;
use NumPHP\Exception\MissingArgumentException;

class NumArray
{
    private $data;

    private $string;

    private $shape;

    private $size;

    private $nDim;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __toString(): string
    {
        if (is_null($this->string)) {
            $this->string = self::recursiveToString($this->data);
        }
        return $this->string;
    }

    public function isEqual(NumArray $numArray): bool
    {
        return get_class($this) === get_class($numArray) && $this->getData() === $numArray->getData();
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getShape(): array
    {
        if (is_null($this->shape)) {
            $shape = [];
            $currentDataDim = $this->data;
            while (is_array($currentDataDim)) {
                $shape[] = count($currentDataDim);
                $currentDataDim = $currentDataDim[0];
            }
            $this->shape = $shape;
        }
        return $this->shape;
    }

    public function getSize(): int
    {
        if (is_null($this->size)) {
            $this->size = array_reduce(
                $this->getShape(),
                function ($carry, $item) {
                    return $carry * $item;
                },
                1
            );
        }
        return $this->size;
    }

    public function getNDim(): int
    {
        if (is_null($this->nDim)) {
            $this->nDim = count($this->getShape());
        }
        return $this->nDim;
    }

    public function combine(NumArray $numArray, callable $func): NumArray
    {
        if ($this->getShape() !== $numArray->getShape()) {
            throw new IllegalArgumentException(sprintf(
                "Shape [%s] and [%s] are different",
                implode(', ', $this->getShape()),
                implode(', ', $numArray->getShape())
            ));
        }
        return new NumArray(self::recursiveArrayCombine($func, $this->getData(), $numArray->getData()));
    }

    public function add(NumArray $numArray): NumArray
    {
        return $this->combine($numArray, function ($val1, $val2) {
            return $val1 + $val2;
        });
    }

    public function sub(NumArray $numArray): NumArray
    {
        return $this->combine($numArray, function ($val1, $val2) {
            return $val1 - $val2;
        });
    }

    public static function ones(int ...$axis): NumArray
    {
        if (empty($axis)) {
            throw new MissingArgumentException('No $axis given');
        }
        return new NumArray(self::recursiveFillArray($axis, 1));
    }

    public static function onesLike(NumArray $numArray): NumArray
    {
        $shape = $numArray->getShape();
        return self::ones(...$shape);
    }

    public static function zeros(int ...$axis): NumArray
    {
        if (empty($axis)) {
            throw new MissingArgumentException('No $axis given');
        }
        return new NumArray(self::recursiveFillArray($axis, 0));
    }

    public static function zerosLike(NumArray $numArray): NumArray
    {
        $shape = $numArray->getShape();
        return self::zeros(...$shape);
    }

    private static function recursiveToString(array $data, int $level = 0): string
    {
        $indent = str_repeat("  ", $level);
        if (isset($data[0]) && is_array($data[0])) {
            $nextLevel = $level + 1;
            $result = array_map(function ($entry) use ($nextLevel) {
                return self::recursiveToString($entry, $nextLevel);
            }, $data);
            return sprintf("%s[\n%s\n%s]", $indent, implode(",\n", $result), $indent);
        }
        return sprintf("%s[%s]", $indent, implode(", ", $data));
    }

    private static function recursiveFillArray(array $shape, $value): array
    {
        if (count($shape) === 1) {
            return array_fill(0, array_shift($shape), $value);
        }
        return array_fill(0, array_shift($shape), self::recursiveFillArray($shape, $value));
    }

    private static function recursiveArrayCombine(callable $func, array $arr1, array $arr2): array
    {
        if (isset($arr1[0]) && is_array($arr1[0])) {
            return array_map(function ($val1, $val2) use ($func) {
                return self::recursiveArrayCombine($func, $val1, $val2);
            }, $arr1, $arr2);
        }
        return array_map($func, $arr1, $arr2);
    }
}
