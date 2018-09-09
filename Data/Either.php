<?php

namespace thgs\HaskellPHP\Data;

class Either
{
    /*--------------------------------------------------------------------------
    | Either Class
    |---------------------------------------------------------------------------
    |
    | Base class to represent Either type. Also implements basic functionality
    |
    */

    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

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

    /*--------------------------------------------------------------------------
    | Left & Right Classes > Maybe
    |---------------------------------------------------------------------------
    |
    | Classes to represent Left or Right data types.
    |
    */

class Left extends Either
{
}

class Right extends Either
{
}

    /*--------------------------------------------------------------------------
    | Function aliases
    |---------------------------------------------------------------------------
    |
    | These functions exist to simplify object generation of Left or Right
    | values.
    |
    | Usage:    left($value)        instead of      new Left($value)
    |           right($value)       instead of      new Right($value)
    |
    */

function left($value)
{
    return new Left($value);
}

function right($value)
{
    return new Right($value);
}

    /*--------------------------------------------------------------------------
    | Functions to be used in conditions
    |---------------------------------------------------------------------------
    |
    | Simple isLeft and isRight functions to be used in conditions.
    |
    */

function isLeft(Either $ab)
{
    return $ab instanceof Left;
}

function isRight(Either $ab)
{
    return $ab instanceof Right;
}

    /*--------------------------------------------------------------------------
    | either function
    |---------------------------------------------------------------------------
    |
    | From Haskell comments
    |
    | -- If the value is @'Left' a@, apply the first function to @a@;
    | -- if it is @'Right' b@, apply the second function to @b@.
    |
    | Implementation in Haskell
    |
    | either                  :: (a -> c) -> (b -> c) -> Either a b -> c
    | either f _ (Left x)     =  f x
    | either _ g (Right y)    =  g y
    |
    */

function either(callable $f, callable $g, Either $ab)
{
    $func = (isLeft($ab)) ? $f : $g;

    return $func($ab());
}

    /*--------------------------------------------------------------------------
    | lefts & rights functions
    |---------------------------------------------------------------------------
    |
    |               lefts
    |
    | From Haskell comments
    |
    | -- | Extracts from a list of 'Either' all the 'Left' elements.
    | -- All the 'Left' elements are extracted in order.
    |
    | Implementation in Haskell
    |
    | lefts   :: [Either a b] -> [a]
    | lefts x = [a | Left a <- x]
    |
    |               rights
    |
    | From Haskell comments
    |
    | -- | Extracts from a list of 'Either' all the 'Right' elements.
    | -- All the 'Right' elements are extracted in order.
    |
    | Implementation in Haskell
    |
    | rights   :: [Either a b] -> [b]
    | rights x = [a | Right a <- x]
    |
    */

function lefts(array $a)
{
    return array_filter($a, function ($value) {
        return isLeft($value);
    });
}

function rights(array $a)
{
    return array_filter($a, function ($value) {
        return isLeft($value);
    });
}

    /*--------------------------------------------------------------------------
    | partitionEithers function
    |---------------------------------------------------------------------------
    |
    | From Haskell comments
    |
    | -- | Partitions a list of 'Either' into two lists.
    | -- All the 'Left' elements are extracted, in order, to the first
    | -- component of the output.  Similarly the 'Right' elements are extracted
    | -- to the second component of the output.
    |
    | Implementation in Haskell
    |
    | partitionEithers :: [Either a b] -> ([a],[b])
    | partitionEithers = foldr (either left right) ([],[])
    |  where
    |   left  a ~(l, r) = (a:l, r)
    |   right a ~(l, r) = (l, a:r)
    |
    */

function partitionEithers(array $ab)
{
    $lefts = [];
    $rights = [];

    foreach ($ab as $element) {
        if (isLeft($element)) {
            $lefts[] = $element();
        }
        if (isRight($element)) {
            $rights[] = $element();
        }
    }

    return [$lefts, $rights];
}
