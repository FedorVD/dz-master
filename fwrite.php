<?php


$file = fopen(__DIR__ . 'data.txt', 'a');

fputs($file, $_SERVER['REMOTE_ADDR'] ."\n");

fclose($file);


