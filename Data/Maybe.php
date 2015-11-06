<?php namespace thgs\HaskellPHP\Data;


/**
 * Maybe Class.
 */
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

/* testing init */
/*
$maybe = new Maybe; echo get_class($maybe)  .PHP_EOL;
$just = new Just(4); echo $just .PHP_EOL;
$nothing = new Nothing; var_dump($nothing());
*/

function just($value)
{
    return new Just($value);
}

function nothing()
{
    return new Nothing();
}

/* testing aliases functions */
/*
$just = just(3);
$nothing = nothing();
var_dump($just, $nothing);
*/

# maybe :: b -> (a -> b) -> Maybe a -> b
function maybe($b, callable $f, Maybe $a)
{
    return ($a instanceof Nothing) ? $b : $f($a());
}

/* testing maybe function */

$f = function ($a) { return ((int) $a) + 4; };
$one = maybe(2, $f, just(2));
$two = maybe(1, $f, nothing());
var_dump($one, $two);

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

/*
-- | The 'mapMaybe' function is a version of 'map' which can throw
-- out elements.  In particular, the functional argument returns
-- something of type @'Maybe' b@.  If this is 'Nothing', no element
-- is added on to the result list.  If it is @'Just' b@, then @b@ is
-- included in the result list.
--
-- ==== __Examples__
--
-- Using @'mapMaybe' f x@ is a shortcut for @'catMaybes' $ 'map' f x@
-- in most cases:
--
-- >>> import Text.Read ( readMaybe )
-- >>> let readMaybeInt = readMaybe :: String -> Maybe Int
-- >>> mapMaybe readMaybeInt ["1", "Foo", "3"]
-- [1,3]
-- >>> catMaybes $ map readMaybeInt ["1", "Foo", "3"]
-- [1,3]
--
-- If we map the 'Just' constructor, the entire list should be returned:
--
-- >>> mapMaybe Just [1,2,3]
-- [1,2,3]
--
mapMaybe          :: (a -> Maybe b) -> [a] -> [b]
mapMaybe _ []     = []
mapMaybe f (x:xs) =
 let rs = mapMaybe f xs in
 case f x of
  Nothing -> rs
  Just r  -> r:rs
  
 */

function mapMaybe(callable $f, array $a)
{
    return (empty($a)) ? [] : catMaybes(array_map($f, $a));
}

/*
function mapMaybe2(callable $f, array $a)
{
    foreach ($a as $element)
    {
        $fx = $f($element);
        if 
    }
}
*/

/* test mapMaybe */
/*
$l = [1, "a"];
var_dump(
    mapMaybe(function ($a) {
        return (is_string($a)) ? just($a) : nothing();
    }, $l)
);
*/
