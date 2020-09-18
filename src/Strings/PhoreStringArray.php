<?php


namespace Phore\Misc\Strings;


class PhoreStringArray
{
    private $arr;

    public function __construct(array $array)
    {
        $this->arr = $array;
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

        return new PhoreStringArray($ret);
    }

    public function implode(string $glue = "") : PhoreString
    {
        return new PhoreString(implode($glue, $this->arr));
    }


    public function call(callable $cb) : self
    {
        $val = $cb(...$this->arr);
        if ($val === null)
            return new PhoreStringArray([]);
        return new PhoreStringArray($val);
    }
}
