<?php


namespace Phore\Misc\Strings;




class PhoreString
{
    private $str;


    public function __construct(string $str)
    {
        $this->str = $str;
    }

    public function explode($delimiter, $limit=null) : PhoreStringArray
    {
        return new PhoreStringArray(explode($delimiter, $this->str, $limit));
    }

    public function splitLines() : PhoreStringArray
    {
        $split = str_replace("\r\n", "\n", $this->str);
        return new PhoreStringArray(explode("\n", $split));
    }

    public function regex(string $regex) : PhoreRegex
    {
        return new PhoreRegex($regex, $this->str);
    }

    public function __toString()
    {
        return $this->str;
    }

}
