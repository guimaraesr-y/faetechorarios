<?php

require __DIR__ . '/vendor/autoload.php';

$root = $_SERVER['DOCUMENT_ROOT'];
$envFilepath = "$root/.env";

// configures .env file
if (is_file($envFilepath)) {
    $file = new \SplFileObject($envFilepath);

    while ($file->eof() === false) {
        $str = $file->fgets();
        $str = trim($str);
        
        if($str === '') continue;
        
        putenv($str);
    }
}
