<?php

namespace Thettler\ExtendedLocalization\Contracts;

interface Language
{
    /**
     * @return array<string>
     */
    public static function getCodes():array;
    public function getCode(): string;
}
