<?php

namespace Phore\Misc\Objects;

use Phore\Core\Exception\NotFoundException;

trait PhoreObjectMaintainer
{

    /**
     * Find an Object with properties matching selector in an array
     *
     * If parameter 3 is true, it will create and return a new Instance of itself
     * in case the selector properties were not found.
     *
     * <example>
     * // Select first matching object
     * $fredContact = Contact::selectFormArray($phonebook->contacts, ["name" => "Fred"]);
     * </example>
     *
     * @param array $haystack
     * @param array $selector
     * @param bool $createIfNotFound
     * @param null $default
     * @return static
     * @throws NotFoundException
     */
    public static function maintain(array &$haystack, array $selector, bool $createIfNotFound=false, $default=null) : self
    {
        foreach ($haystack as $cur) {
            if ( ! $cur instanceof self)
                throw new \InvalidArgumentException("property is required to be of type");
            foreach ($selector as $propName => $val) {
                if ($cur->$propName !== $val)
                    continue 2;
            }
            return $cur;
        }

        if ($createIfNotFound === true) {
            $instance = new self();
            foreach ($selector as $propName => $val)
                $instance->$propName = $val;
            $haystack[] = $instance;
            return $instance;
        }
        if ($default instanceof \Exception)
            throw $default;
        throw new NotFoundException("Selection didn't return a result");
    }

}
