#!/usr/bin/env php
<?php
require 'Pesel.php';

$peselList = [
    new Pesel('-2222222222'),    // bad
    new Pesel('22222222222'),    // bad
    new Pesel('00000000000'),    // good, but date wrong
    new Pesel('0'),              // bad
    new Pesel('asza8898aaa'),    // bad
    new Pesel('999999999999999'),// bad
    new Pesel('73070807059'),    // good
    new Pesel('70091013427'),    // good
    new Pesel('17060804103'),    // good
];

function yn($val) {
    return ((bool) $val ? 'good' : 'bad');
}

foreach ($peselList as $key => $pesel) {
    echo '#' . ($key + 1) . ': PESEL = ' . $pesel . "\n";
    echo "\tFormat: " . yn($pesel->validateFormat()) . "\n";
    echo "\tChecksum: " . yn($pesel->validateChecksum()) . "\n";
    echo "\tDate: " . (($date = $pesel->getDate()) ? ('good (' . $date->format('Y-m-d') . ')') : 'bad') . "\n\n";
}
