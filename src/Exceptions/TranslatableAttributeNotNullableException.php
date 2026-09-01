<?php

namespace Thettler\ExtendedLocalization\Exceptions;


final class TranslatableAttributeNotNullableException extends \Exception {

    public static function throw(string $attribute, string $model):never
    {
        throw new static("The TranslatableAttribute \"{$attribute}\" in {$model} is not nullable. Try adding nullable to the cast if you want to allow null values: \"{$attribute}\" => TranslatableAttribute::class.':nullable'");
    }
}
