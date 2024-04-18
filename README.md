## Uzbek-transliterator

Kirill alifbosidagi oʻzbekcha matnni lotinchaga va aksincha transliteratsiya qilish uchun. https://uz.wikipedia.org/wiki/Vikipediya:O%CA%BBzbek_lotin_alifbosi_qoidalari

## Yuklab olish
<code>composer require shoyim/uzbek-transliterate</code>

## Ishlatib ko'rish
<code>
  """
    <?php
    require_once 'src/global-var.php';
    require_once 'src/Transliterator.php';
    // Test
    $obj = new Transliteration\Transliterator();

    echo $obj->transliterate("Assalomu aleykum","kiril");
  """
</code>
