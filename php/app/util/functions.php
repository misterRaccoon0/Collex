<?php
namespace Util\Functions;

// @param array $array
function addOrAppendUnsafe(array $array, string $key, $func_or_class){
    if(isset($array[$key])){
	$array[$key] = $func_or_class;
    } else{
	array_push($array, $func_or_class);
    }
}
