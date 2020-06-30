<?php
spl_autoload_register(function($class) {

    $a = explode('\\', $class);
    $last = array_pop($a);
    $fn = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $last . '.php';
    $fn = str_replace('\\', '/', $fn);

    //echo '<b>autoload: ' . $class . '</b> file: ' . $fn . '<br>';

    if (file_exists($fn)) require $fn;
});