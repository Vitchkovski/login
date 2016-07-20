<?php

function validateIfPasswordSecure($password)
{
if (strlen($password) < 6)
return false;

return true;
}

function explodeWithMultipleDelimiters ($delimiters, $string) {

    $multipleDelimitersIntoOne = str_replace($delimiters, $delimiters[0], $string);
    $explodeResult = explode($delimiters[0], $multipleDelimitersIntoOne);
    return  $explodeResult;
}

function clearArray($array) {
    return array();
}

function categoryStringToArray ($escapedProductCategoriesString){

    $categoryDelimitersList = array(",", ";", ", ", "; ");


    $productCategoriesArray = explodeWithMultipleDelimiters($categoryDelimitersList, $escapedProductCategoriesString);
    for ($i = 0; $i <= 4; $i++) {
        if (isset($productCategoriesArray[$i])) {
            $limitedCategoriesArray[$i] = $productCategoriesArray[$i];
        } else {
            $limitedCategoriesArray[$i] = null;
        }

    }

    return $limitedCategoriesArray;

}

?>