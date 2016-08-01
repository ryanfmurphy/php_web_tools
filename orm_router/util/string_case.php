<?php

function CapCase2camelCase($strn) { return lcfirst($strn); }
function camelCase2CapCase($strn) { return ucfirst($strn); }

function snake_case2CapCase($strn) {
    $words = str_replace('_', ' ', $strn);
    return str_replace(' ', '', ucwords($words));
}

function snake_case2camelCase($strn) {
    return CapCase2camelCase(
        snake_case2CapCase($strn)
    );
}

function camelCase2snake_case($strn) {
    return strtolower(
        preg_replace('/[A-Z]/', '_\0', $strn)
    );
}

function CapCase2snake_case($strn) {
    return camelCase2snake_case(CapCase2camelCase($strn));
}



function table_name2ClassName($strn) {
    return snake_case2CapCase($strn);
}

function ClassName2table_name($strn) {
    return CapCase2snake_case($strn);
}

