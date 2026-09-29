<?php

declare(strict_types=1);

namespace Thettler\ExtendedLocalization\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Thettler\ExtendedLocalization\TranslatableAttribute;

trait HasTranslatableAttributes
{
    public function getTranslatableAttributes(): array
    {
        return array_filter($this->getCasts(), (fn ($value) => str_starts_with($value, TranslatableAttribute::class) ? true : false))
                |>array_keys(...);
    }

    public function isTranslatableAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getTranslatableAttributes());
    }

    /**
     * @param  null|Language[]  $languages
     */
    public function scopeWhereTranslatableAttribute(Builder $query, string $attribute, string $value, ?array $languages = null): Builder
    {
        return $query
            ->where(function (Builder $query) use ($attribute, $value, $languages) {
                $languages = $languages ?? config('extended-localization.language_enum')::cases();
                foreach ($languages as $language) {
                    $query->orWhereJsonContains($attribute.'->'.$language->getCode(), $value);
                }
            });
    }

    public function scopeWhereTranslatableLikeAttribute(Builder $query, string $attribute, string $value, ?array $languages = null): Builder
    {
        return $query
            ->where(function (Builder $query) use ($attribute, $value, $languages) {
                $languages = $languages ?? config('extended-localization.language_enum')::cases();
                foreach ($languages as $language) {
                    $query->orWhere($attribute.'->'.$language->getCode(), 'like', "%{$value}%");
                }
            });
    }

    public function getAttribute($key)
    {
        if (! str_contains($key, '.')) {
            return parent::getAttribute($key);
        }
        $segments = explode('.', $key);

        if (! $this->isTranslatableAttribute($segments[0])) {
            return parent::getAttribute($key);
        }

        return $this->{$segments[0]}->getTranslation($segments[1]);
    }
}
