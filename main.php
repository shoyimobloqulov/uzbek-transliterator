<?php
    require_once 'src/global-var.php';
    require_once 'src/Transliterator.php';
    // Test
    $obj = new Transliteration\Transliterator();

    echo $obj->transliterate("Assalomu aleykum","kiril");

