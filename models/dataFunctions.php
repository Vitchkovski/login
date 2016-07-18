<?php

function validateIfPasswordSecure($password)
{
if (strlen($password) < 6)
return false;

return true;
}

function explodeWithMultipleDelimeters ($delimiters, $string) {

    $multipleDelimetersIntoOne = str_replace($delimiters, $delimiters[0], $string);
    $explodeResult = explode($delimiters[0], $multipleDelimetersIntoOne);
    return  $explodeResult;
}

function clearArray($array) {
    return array();
}

?>