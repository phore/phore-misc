<?php

function phore_misc_string($str) : \Phore\Misc\Strings\PhoreString
{
    if ($str instanceof \Phore\Misc\Strings\PhoreString)
        return $str;
    return new \Phore\Misc\Strings\PhoreString($str);
}


function phore_misc_array(array $array) : \Phore\Misc\Arrays\PhoreArray
{
    return new \Phore\Misc\Arrays\PhoreArray($array);
}
