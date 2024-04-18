<?php
    include_once 'sh-transliterate/functions.php';
    include_once 'sh-transliterate/data-var.php';

    // Test
    $obj = new TextTransliterate();

    echo $obj->transliterate("Assalomu aleykum","kiril");

