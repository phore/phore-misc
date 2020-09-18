<?php

function phore_misc_string($str) : \Phore\Misc\Strings\PhoreString
{
    if ($str instanceof \Phore\Misc\Strings\PhoreString)
        return $str;
    return new \Phore\Misc\Strings\PhoreString($str);
}
