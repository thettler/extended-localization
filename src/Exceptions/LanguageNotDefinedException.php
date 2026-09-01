<?php

namespace Thettler\ExtendedLocalization\Exceptions;

use Thettler\ExtendedLocalization\Contracts\Language;

final class LanguageNotDefinedException extends \Exception
{
    /**
     * @param  class-string<Language>  $languageEnum
     *
     * @throws LanguageNotDefinedException
     */
    public static function throw(string $language, string $languageEnum): never
    {
        throw new self("Language \"{$language}\" does not exist in Language Enum. Available languages: ".implode(', ', $languageEnum::getCodes()));
    }
}
