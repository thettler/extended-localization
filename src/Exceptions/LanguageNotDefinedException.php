<?php

namespace Thettler\ExtendedLocalization\Exceptions;

use Thettler\ExtendedLocalization\Contracts\Language;

final class LanguageNotDefinedException extends \Exception {

    /**
     * @param  string  $language
     * @param  class-string<Language>  $languageEnum
     * @return never
     * @throws LanguageNotDefinedException
     */
    public static function throw(string $language, string $languageEnum):never
    {
        throw new static("Language \"{$language}\" does not exist in Language Enum. Available languages: ".implode(', ', $languageEnum::getCodes()));
    }
}
