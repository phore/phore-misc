<?php


namespace Phore\Misc\Map;


class PhoreMap
{

    private $count = 0;
    private $map = [];

    public function get($key, $default = null)
    {
        if ( ! isset ($this->map[$key])) {
            if ($default === null)
                return null;
            $this->count++;
            $this->map[$key] = $default;
        }
        return $this->map[$key];
    }

    public function count()
    {
        return $this->count;
    }

    public function set($key, $value)
    {
        if ( ! isset ($this->map[$key]))
            $this->count++;
        $this->map[$key] = $value;
    }

    public function has($key) : bool
    {
        return isset ($this->map[$key]);
    }

}
