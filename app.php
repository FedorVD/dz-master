<?php

/*require_once 'src/blog.php';
require_once 'src/helpers.php';
require_once 'src/main.php';*/

require __DIR__ . '/vendor/autoload.php';

try {
    $result = main();
} catch (PDOException $e) {
    echo $e->getMessage();
}
catch (Exception $e){
    echo handleError($e->getMessage());
}
echo $result;