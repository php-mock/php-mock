<?php

// Compatibility with phpunit/php-text-template v2

if (!class_exists(SebastianBergmann\Template\Template::class)
    && class_exists(Text_Template::class)
) {
    class_alias(Text_Template::class, SebastianBergmann\Template\Template::class);
}
