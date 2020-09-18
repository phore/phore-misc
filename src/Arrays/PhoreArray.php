<?php


namespace Phore\Misc\Arrays;


use Phore\Misc\Strings\PhoreString;

class PhoreArray
{

    private $arr;

    public function __construct(array $input)
    {
        $this->arr = $input;
    }


    public function map(callable $fn) : self
    {
        $ret = [];
        foreach ($this->arr as $key => $value) {
            $val = $fn($key, $value, $this->arr);
            if ($val === null)
                continue;
            $ret[] = $val;
        }

        return new PhoreArray($ret);
    }

    public function inArray($needle) : bool
    {
        return in_array($needle, $this->arr);
    }

    public function join(string $glue="") : PhoreString
    {
        return new PhoreString(implode($glue, $this->arr));
    }

    public function getArray() : array
    {
        return $this->arr;
    }

}
