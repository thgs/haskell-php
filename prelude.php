<?php namespace thgs\HaskellPHP;

/* Boolean functions */

function not($b)
{
    return (! $b);
}



define('OTHERWISE', true);



/* Tuples */


    /*--------------------------------------------------------------------------
    | fst and snd functions
    |---------------------------------------------------------------------------
    |
    |                           fst
    |
    | From Haskell comments
    |
    | -- | Extract the first component of a pair.
    |
    | Implementation in Haskell
    |
    | fst                     :: (a,b) -> a
    | fst (x,_)               =  x
    |
    |                           snd
    |
    | From Haskell comments
    |
    | -- | Extract the second component of a pair.
    |
    | snd                     :: (a,b) -> b
    | snd (_,y)               =  y
    |
    |
    | Both fst and snd functions are implemented using array_shift in PHP,
    | so they can be used with any kind of array representing a tuple.
    |
    */


function fst($tuple)
{
    return array_shift($tuple);
}

function snd($tuple)
{
    array_shift($tuple);
    
    return array_shift($tuple);
}


    /*--------------------------------------------------------------------------
    | curry and uncurry functions
    |---------------------------------------------------------------------------
    |
    | -- | 'curry' converts an uncurried function to a curried function.
    | curry                   :: ((a, b) -> c) -> a -> b -> c
    | curry f x y             =  f (x, y)
    |
    | -- | 'uncurry' converts a curried function to a function on pairs.
    | uncurry                 :: (a -> b -> c) -> ((a, b) -> c)
    | uncurry f p             =  f (fst p) (snd p)
    */

function curry(callable $f, $a, $b)
{
    return $f([$a, $b]);
}

function uncurry(callable $f, $tuple)
{
    return $f($tuple);
}



/* a lot more.... */


function id($x) 
{ 
    return $x; 
}

function const_($a, $b) 
{ 
    return $a; 
}

/*
(.)    :: (b -> c) -> (a -> b) -> a -> c
(.) f g = \x -> f (g x)
*/
function compose($f, $g, $a)
{
    return function($x) use ($f, $g) { return $f($g($x)); };
}

function c($f, $g, $a)
{
    return compose($f, $g, $a);
}


function flip($f, $x, $y)
{
    return $f($x, $y);
}

# ($) :: (a -> b) -> a -> b infixr 0
function apply($f, $a)
{
    return $f($a);
}

function a($f, $a)
{
    return apply($f, $a);
}

# until :: (a -> Bool) -> (a -> a) -> a -> a
function until($condition, $f, $a)
{
    while($condition)
    {
        $a = $f($a);
    }
    
    return $a;
}



/* a lot more ... */


function putChar($c) 
{ 
    echo $c;
}
function putStr($s) 
{ 
    echo $s;
}

function putStrLn($s) 
{ 
    echo $s.PHP_EOL; 
}

# print ?

function getChar() 
{
    $stdin = fopen('php://stdin');

    $c = fgetc($stdin);

    fclose($stdin);

    return $c;
}

function getLine()
{
    $stdin = fopen('php://stdin');
    
    $line = fgets($stdin);
    
    fclose($stdin);
    
    return $line;
}

# not sure if that is politically correct..
function getContents()
{
    return function () { return getLine(); };
}



function interact($f)
{
    return putStr($f(getLine()));
}

# generic interact
function gInteract($f, $f1 = 'putStr', $f2 = 'getLine')
{
    return $f1($f($f2()));
}


function readFile($filepath)
{
    return file_get_contents($filepath);
}

function writeFile($filepath, $string)
{
    return file_put_contents($filepath, $string);
}

function appendFile($filepath, $string)
{
    return file_put_contents($filepath, $string, FILE_APPEND);
}



function map($f, array $list)
{
    return array_map($f, $list);
}


function append($a, $b)
{
    return array_merge($a, $b);
}

function filter($predicate, $a) 
{
    return array_filter($a, $predicate);
}

function head($a)
{
    return array_shift($a);
}

function last($a)
{
    return array_pop($a);
}

function tail($a)
{
    array_shift($a);
    
    return $a;
}

function init($a)
{
    array_pop($a);
    
    return $a;
}
