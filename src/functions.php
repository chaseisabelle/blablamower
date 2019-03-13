<?php
function is_odd($i) {
    return $i % 2 !== 0;
}

function is_even($i) {
    return !is_odd($i);
}

function inc_if_less(&$i, $n) {
    if ($i < $n) {
        $i++;
    }
}

function dec_if_greater(&$i, $n = 0) {
    if ($i > $n) {
        $i--;
    }
}

function println($line) {
    print "$line\n";
}

function dbg() {
    $args = func_get_args();

    foreach ($args as $arg) {
        var_dump($arg);
    }
}