<?php
    namespace Transliteration;
    class Transliterator
    {
        public function to_cyrillic($text) {
            $compounds_first = array(
                'ch' => 'ч', 'Ch' => 'Ч', 'CH' => 'Ч',
                'sh' => 'ш', 'Sh' => 'Ш', 'SH' => 'Ш',
                'yo‘' => 'йў', 'Yo‘' => 'Йў', 'YO‘' => 'ЙЎ'
            );

            $compounds_second = array(
                'yo' => 'ё', 'Yo' => 'Ё', 'YO' => 'Ё',
                'yu' => 'ю', 'Yu' => 'Ю', 'YU' => 'Ю',
                'ya' => 'я', 'Ya' => 'Я', 'YA' => 'Я',
                'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
                'o‘' => 'ў', 'O‘' => 'Ў', 'oʻ' => 'ў', 'Oʻ' => 'Ў',
                'g‘' => 'ғ', 'G‘' => 'Ғ', 'gʻ' => 'ғ', 'Gʻ' => 'Ғ'
            );

            $beginning_rules = array(
                'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
                'e' => 'э', 'E' => 'Э'
            );

            $after_vowel_rules = array(
                'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
                'e' => 'э', 'E' => 'Э'
            );

            $exception_words_rules = array(
                's' => 'ц', 'S' => 'Ц',
                'ts' => 'ц', 'Ts' => 'Ц', 'TS' => 'Ц',
                'e' => 'э', 'E' => 'э',
                'sh' => 'сҳ', 'Sh' => 'Сҳ', 'SH' => 'СҲ',
                'yo' => 'йо', 'Yo' => 'Йо', 'YO' => 'ЙО',
                'yu' => 'йу', 'Yu' => 'Йу', 'YU' => 'ЙУ',
                'ya' => 'йа', 'Ya' => 'Йа', 'YA' => 'ЙА'
            );

            // Standardize some characters
            $text = str_replace('ʻ', '‘', $text);

            // Replace soft sign words
            $text = preg_replace_callback(
                '/\b('.implode('|', SOFT_SIGN_WORDS).')\b/u',
                function($matches) {
                    $word = $matches[1];
                    $result = SOFT_SIGN_WORDS[strtolower($word)] ?? $word;
                    if (ctype_upper($word)) {
                        $result = strtoupper($result);
                    } elseif (ctype_upper($word[0])) {
                        $result = ucfirst($result);
                    }
                    return $result;
                },
                $text
            );

            // Replace exception words
            $pattern = '/\b('.implode('|', array_merge(array_keys(TS_WORDS), array_keys(E_WORDS))).')\b/u';
            $text = preg_replace_callback(
                $pattern,
                function($matches) use ($exception_words_rules) {
                    return preg_replace(
                        '/'.$matches[2].'/u',
                        $exception_words_rules[$matches[2]],
                        $matches[1]
                    );
                },
                $text
            );

            // Compounds
            $text = preg_replace_callback(
                '/('.implode('|', array_keys($compounds_first)).')/u',
                function($matches) use ($compounds_first) {
                    return $compounds_first[$matches[1]];
                },
                $text
            );

            $text = preg_replace_callback(
                '/('.implode('|', array_keys($compounds_second)).')/u',
                function($matches) use ($compounds_second) {
                    return $compounds_second[$matches[1]];
                },
                $text
            );

            $text = preg_replace_callback(
                '/\b('.implode('|', array_keys($beginning_rules)).')/u',
                function($matches) use ($beginning_rules) {
                    return $beginning_rules[$matches[1]];
                },
                $text
            );

            $text = preg_replace_callback(
                '/('.implode('|', array_merge(LATIN_VOWELS, array_keys($after_vowel_rules))).')('.implode('|', array_keys($after_vowel_rules)).')/u',
                function($matches) use ($after_vowel_rules) {
                    return $matches[1].$after_vowel_rules[$matches[2]];
                },
                $text
            );
            $LATIN_TO_CYRILLIC = LATIN_TO_CYRILLIC;
            $text = preg_replace_callback(
                '/('.implode('|', array_keys(LATIN_TO_CYRILLIC)).')/u',
                function($matches) use ($LATIN_TO_CYRILLIC) {
                    return LATIN_TO_CYRILLIC[$matches[1]];
                },
                $text
            );

            return $text;
        }
        public function to_latin($text) {
            $beginning_rules = array(
                'ц' => 's', 'Ц' => 'S',
                'е' => 'ye', 'Е' => 'Ye'
            );

            $after_vowel_rules = array(
                'ц' => 'ts', 'Ц' => 'Ts',
                'е' => 'ye', 'Е' => 'Ye'
            );

            $text = preg_replace_callback(
                '/(сент|окт)([яЯ])(бр)/u',
                function($matches) {
                    return $matches[1] . ($matches[2] == 'я' ? 'a' : 'A') . $matches[3];
                },
                $text
            );

            $text = preg_replace_callback(
                '/\b('.implode('|', array_keys($beginning_rules)).')/u',
                function($matches) use ($beginning_rules) {
                    return $beginning_rules[$matches[1]];
                },
                $text
            );

            $text = preg_replace_callback(
                '/('.implode('|', array_merge(CYRILLIC_VOWELS, array_keys($after_vowel_rules))).')('.implode('|', array_keys($after_vowel_rules)).')/u',
                function($matches) use ($after_vowel_rules) {
                    return $matches[1].$after_vowel_rules[$matches[2]];
                },
                $text
            );
            $CYRILLIC_TO_LATIN = CYRILLIC_TO_LATIN;
            return preg_replace_callback(
                '/('.implode('|', array_keys(CYRILLIC_TO_LATIN)).')/u',
                function($matches) use ($CYRILLIC_TO_LATIN) {
                    return CYRILLIC_TO_LATIN[$matches[1]];
                },
                $text
            );
        }

        public function transliterate($text, $to_variant) {
            if ($to_variant == 'kiril') {
                $text = $this->to_cyrillic($text);
            } elseif ($to_variant == 'lotin') {
                $text = $this->to_latin($text);
            }

            return $text;
        }
    }
?>
