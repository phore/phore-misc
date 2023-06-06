<?php


namespace Phore\Misc\Strings;

function declaresArray(\ReflectionParameter $reflectionParameter): bool
{
    $reflectionType = $reflectionParameter->getType();

    if (!$reflectionType) return false;

    $types = $reflectionType instanceof ReflectionUnionType
        ? $reflectionType->getTypes()
        : [$reflectionType];

   return in_array('array', array_map(fn(ReflectionNamedType $t) => $t->getName(), $types));
}
class PhoreRegex
{

    public $regex;
    public $input;

    public function __construct(string $regex, string $input)
    {
        $this->regex = $regex;
        $this->input = $input;
    }


    public function match(&$result=null) : bool
    {

    }


    /**
     * Replace
     *
     * Named matches (specified by '(?<name>...)' will be used as parameters for callback
     *
     * <example>
     * $result = phore_string("hello bob.")->regex("/^Hello (?<name>\w+)\./")->replaceCallback(function($name) {
     *      return "not $name";
     * });
     * echo $result;
     * </example>
     *
     * @param callable $callback
     * @param int $limit
     * @param int|null $count
     * @return PhoreString
     * @throws \ReflectionException
     */
    public function replaceCallback(callable $callback, int $limit=-1, int &$count=null) : PhoreString
    {
        $ref = new \ReflectionFunction($callback);
        $out = preg_replace_callback($this->regex, function ($matches) use ($callback, $ref) {
            $params = [];
            foreach($ref->getParameters() as $parameter) {
                if ($parameter->name === "matches") {
                    $params[] = $matches;
                    continue;
                }
                if (isset ($matches[$parameter->name])) {
                    $value = $matches[$parameter->name];
                    if (declaresArray($parameter) && !is_array($value))
                        $value = [$value];
                    $params[] = $value;
                    continue;
                }
                if ($parameter->isOptional()) {
                    $params[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new \InvalidArgumentException("Missing key '{$parameter->name}' in matches");
            }
            return $callback(...$params);
        }, $this->input, $limit, $count);
        if ($out === null)
            throw new \InvalidArgumentException("Regex-error: " . print_r (error_get_last()["message"]));
        return new PhoreString($out);
    }

}
