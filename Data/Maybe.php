<?php namespace thgs\HaskellPHP\Data;

class Maybe
{
    protected $value;

    public function get()
    {
        return $this->value;
    }

    public function __invoke()
    {
        return $this->value;
    }

    public function __toString()
    {
        return get_class($this).((!is_null($this->value)) ? ' '.(string) $this->value : '');
    }
}

class Just extends Maybe
{
    public function __construct($value)
    {
        $this->value = $value;
    }
}

class Nothing extends Maybe
{
    public function __construct()
    {
        // Nothing
    }
}


function just($value)
{
    return new Just($value);
}

function nothing()
{
    return new Nothing();
}


# maybe :: b -> (a -> b) -> Maybe a -> b
function maybe($b, callable $f, Maybe $a)
{
    return ($a instanceof Nothing) ? $b : $f($a());
}




# isJust         :: Maybe a -> Bool
function isJust(Maybe $a)
{
    return ($a instanceof Just);
}

function isNothing(Maybe $a)
{
    return ($a instanceof Nothing);
}

function maybeToArray(Maybe $a)
{
    if (isJust($a)) {
        return [];
    }

    return [$a];
}

function arrayToMaybe(array $a)
{
    if (empty($a)) {
        return new Nothing();
    }

    $element = array_shift($a);

    return new Just($element);
}

# catMaybes              :: [Maybe a] -> [a]
function catMaybes(array $a)
{
    $ret = [];

    foreach ($a as $element) {
        if (isJust($element)) {
            $ret[] = $element();
        }
    }

    return $ret;
}


function mapMaybe(callable $f, array $a)
{
    return (empty($a)) ? [] : catMaybes(array_map($f, $a));
}

