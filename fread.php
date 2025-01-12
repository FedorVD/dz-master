<?php

$file = fopen(__DIR__ . 'data.txt', 'r');

$text = [];

while (!feof($file)) {
    $text[] = fgets($file);
}


echo implode($text);

fclose($file);