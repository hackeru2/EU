<?php

if(!function_exists('ColumnNameExists')){

    function ColumnNameExists($arr){

        return $arr->errorInfo[1] == 1062;

    }
}