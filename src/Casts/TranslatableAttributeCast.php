<?php

namespace Thettler\ExtendedLocalization\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Thettler\ExtendedLocalization\Contracts\Translatable;
use Thettler\ExtendedLocalization\Exceptions\TranslatableAttributeNotNullableException;
use Thettler\ExtendedLocalization\TranslatableAttribute;

class TranslatableAttributeCast implements CastsAttributes
{
    protected bool $isNullable = false;

    public function __construct(...$parameters)
    {
        foreach ($parameters as $parameter) {
            if ($parameter === 'nullable') {
                $this->isNullable = true;
            }
        }
    }

    /**
     * @param  Model&Translatable  $model
     * @return never|TranslatableAttribute|null
     *
     * @throws TranslatableAttributeNotNullableException
     */
    public function get(Model $model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->isNullable ? null : TranslatableAttributeNotNullableException::throw($key, $model::class);
        }

        $translations = json_decode($value, true);

        return TranslatableAttribute::new($translations);
    }

    /**
     * @param  Model&Translatable  $model
     * @param  TranslatableAttribute  $value
     * @return mixed|null
     */
    public function set(Model $model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return $this->isNullable ? null : TranslatableAttributeNotNullableException::throw($key, $model::class);
        }

        if (is_array($value)) {
            $value = TranslatableAttribute::new($value);
        }

        return $value->toJson();
    }
}
