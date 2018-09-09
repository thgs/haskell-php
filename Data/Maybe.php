<?php

namespace thgs\HaskellPHP\Data;

class Maybe
{
    /*--------------------------------------------------------------------------
    | Maybe Class
    |---------------------------------------------------------------------------
    |
    | Base class to represent Maybe values. Also implements basic functionality
    |
    */

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
    /*--------------------------------------------------------------------------
    | Just Class > Maybe
    |---------------------------------------------------------------------------
    |
    | Class to represent Just values.
    |
    */

    public function __construct($value)
    {
        $this->value = $value;
    }
}

class Nothing extends Maybe
{
    /*--------------------------------------------------------------------------
    | Nothing Class > Maybe
    |---------------------------------------------------------------------------
    |
    | Class to represent Nothing.
    |
    */

    public function __construct()
    {
        // Nothing
    }
}

    /*--------------------------------------------------------------------------
    | Function aliases
    |---------------------------------------------------------------------------
    |
    | These functions exist to simplify object generation of Just or Nothing
    | values.
    |
    | Usage:    just($value)        instead of      new Just($value)
    |           nothing()           instead of      new Nothing
    |
    */

function just($value)
{
    return new Just($value);
}

function nothing()
{
    return new Nothing();
}

    /*--------------------------------------------------------------------------
    | maybe function
    |---------------------------------------------------------------------------
    |
    | From Haskell comments
    |
    | -- | The 'maybe' function takes a default value, a function, and a 'Maybe'
    | -- value.  If the 'Maybe' value is 'Nothing', the function returns the
    | -- default value.  Otherwise, it applies the function to the value inside
    | -- the 'Just' and returns the result.
    |
    | Implementation in Haskell
    |
    | maybe :: b -> (a -> b) -> Maybe a -> b
    | maybe n _ Nothing  = n
    | maybe _ f (Just x) = f x
    |
    */

function maybe($b, callable $f, Maybe $a)
{
    return ($a instanceof Nothing) ? $b : $f($a());
}

    /*--------------------------------------------------------------------------
    | Functions to be used in conditions
    |---------------------------------------------------------------------------
    |
    | Simple isJust and isNothing functions to be used in conditions.
    |
    */

function isJust(Maybe $a)
{
    return $a instanceof Just;
}

function isNothing(Maybe $a)
{
    return $a instanceof Nothing;
}

    /*--------------------------------------------------------------------------
    | maybeToArray & arrayToMaybe   ->  in Haskell maybeToList & listToMaybe
    |---------------------------------------------------------------------------
    |
    |               maybeToArray    (maybeToList)
    |
    | From Haskell comments
    |
    | -- | The 'maybeToList' function returns an empty list when given
    | -- 'Nothing' or a singleton list when not given 'Nothing'.
    |
    | Implementation in Haskell
    |
    | maybeToList            :: Maybe a -> [a]
    | maybeToList  Nothing   = []
    | maybeToList  (Just x)  = [x]
    |
    |
    |               arrayToMaybe    (listToMaybe)
    |
    | From Haskell comments
    |
    | -- | The 'listToMaybe' function returns 'Nothing' on an empty list
    | -- or @'Just' a@ where @a@ is the first element of the list.
    |
    | Implementation in Haskell
    |
    | listToMaybe           :: [a] -> Maybe a
    | listToMaybe []        =  Nothing
    | listToMaybe (a:_)     =  Just a
    |
    */

function maybeToArray(Maybe $a)
{
    return (isJust($a)) ? [] : [$a];
}

function arrayToMaybe(array $a)
{
    if (empty($a)) {
        return new Nothing();
    }

    $element = array_shift($a);

    return new Just($element);
}

    /*--------------------------------------------------------------------------
    | catMaybes
    |---------------------------------------------------------------------------
    |
    | From Haskell comments
    |
    | -- | The 'catMaybes' function takes a list of 'Maybe's and returns
    | -- a list of all the 'Just' values.
    |
    | Implementation in Haskell
    |
    | catMaybes              :: [Maybe a] -> [a]
    | catMaybes ls = [x | Just x <- ls]
    |
    */

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

    /*--------------------------------------------------------------------------
    | mapMaybe function
    |---------------------------------------------------------------------------
    |
    | From Haskell comments
    |
    | -- | The 'mapMaybe' function is a version of 'map' which can throw
    | -- out elements.  In particular, the functional argument returns
    | -- something of type @'Maybe' b@.  If this is 'Nothing', no element
    | -- is added on to the result list.  If it is @'Just' b@, then @b@ is
    | -- included in the result list.
    |
    | Implementation in Haskell
    |
    | mapMaybe          :: (a -> Maybe b) -> [a] -> [b]
    | mapMaybe _ []     = []
    | mapMaybe f (x:xs) =
    |  let rs = mapMaybe f xs in
    |  case f x of
    |   Nothing -> rs
    |   Just r  -> r:rs
    |
    */

function mapMaybe(callable $f, array $a)
{
    return (empty($a)) ? [] : catMaybes(array_map($f, $a));
}
