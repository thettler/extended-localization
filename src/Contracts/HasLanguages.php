<?php

namespace Thettler\ExtendedLocalization\Contracts;

trait HasLanguages
{
    public static function getCodes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getCode(): string
    {
        return $this->value;
    }
}
