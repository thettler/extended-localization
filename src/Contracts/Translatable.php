<?php

namespace Thettler\ExtendedLocalization\Contracts;

interface Translatable
{
    public function getTranslatableAttributes(): array;

    public function isTranslatableAttribute(string $attribute): bool;
}
